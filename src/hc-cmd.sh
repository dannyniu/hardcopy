#!/bin/sh

# See "doc/Tutorial.html" for usage.

: ${HC_SHARE_PATH:=/usr/share/hardcopy:/usr/local/share/hardcopy:..:.}

hc_dir_test()
(
    IFS=:
    for path in $HC_SHARE_PATH ; do
        if [ -d "$path" ] &&
               [ -d "$path/src-include" ] &&
               [ -f "$path/src-include/hardcopy.php" ] ;
        then
            echo "$path"
            break
        fi
    done
)

export HARDCOPY_DIR=$(hc_dir_test)

if ! [ "$HARDCOPY_DIR" ] ; then
    echo The include source folder for hardcopy is not found!
    exit 1
fi

export HARDCOPY_SRCINC="$HARDCOPY_DIR/src-include"
export HARDCOPY_SRCBLD="$HARDCOPY_DIR/src-include"
export HARDCOPY_SRCINC_MAIN="$HARDCOPY_SRCINC/hardcopy.php"

hcBrowserPreview()
{
    bind="${2:-0.0.0.0}:${1:-8080}"
    echo
    echo Open "\"http://$bind/$(basename "$PWD")/toc.php\"" in browser.
    echo
    php -S "$bind" -t ..
}

__hcBuildVariant__()
{
    variant="$1"

    mkdir -p build/"$variant" build/"$variant"/src-include
    cp $HARDCOPY_SRCINC/*.css build/"$variant"/src-include
    export HARDCOPY_SRCBLD=./src-include

    [ "$HARDCOPY_NOGPL" ] ||
        cp -R \
           $HARDCOPY_SRCINC/gnu-freefont \
           build/"$variant"/src-include

    [ "$HARDCOPY_NOTEX" ] ||
        cp -R \
           $HARDCOPY_SRCINC/tex-gyre \
           $HARDCOPY_SRCINC/tex-gyre-math \
           build/"$variant"/src-include

    if [ -d assets ] ; then
        pax -w -r assets build/"$variant"
        statval=$?
        if [ $statval -eq 127 ] || [ $statval -eq 126 ] ; then
            tar cf - assets | ( cd build/"$variant" ; tar xf - )
        fi
    fi
}

hcBuildMultipage()
{
    __hcBuildVariant__ multipage

    HARDCOPY_OUTPUT_CONTROL=pagelist/ php toc.php | {
        export HARDCOPY_OUTPUT_CONTROL=toc/
        php toc.php > build/multipage/toc.html
        
        while read page ; do
            export HARDCOPY_OUTPUT_CONTROL="$page"
            php toc.php > build/multipage/"$page".html
        done
    }

    HARDCOPY_OUTPUT_CONTROL=pageframe/ > build/multipage/frame.html \
                           php toc.php
}

hcBuildSinglepage()
{
    __hcBuildVariant__ singlepage
    
    HARDCOPY_OUTPUT_CONTROL="" > build/singlepage/main.html \
                           php toc.php
}

log(){ [ X"$verbose" = Xtrue ] && echo "$*" ; }

verbose=false
render=localnet

while getopts vsm opt ; do
    case $opt in
        v) verbose=true;;
        s) render=singlepage;;
        m) render=multipage;;
    esac
done

shift $((OPTIND - 1))

if ! [ -f toc.php ] ; then
    echo '"toc.php" is not found in the current directory!'
    exit 1
fi

case $render in
    localnet) hcBrowserPreview "$@";;
    singlepage) hcBuildSinglepage;;
    multipage) hcBuildMultipage;;
esac
