#!/bin/sh

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

if ! [ -d src-include ] &&
        ! ln -s "$HARDCOPY_DIR/src-include" src-include &&
        ! cp -R "$HARDCOPY_DIR/src-include" src-include ; then
    echo Failed to prepare assets for \"src-include\".
    exit 1
fi

export HARDCOPY_SRCINC="src-include"
export HARDCOPY_SRCINC_MAIN="$HARDCOPY_SRCINC/hardcopy.php"

hcBrowserPreview()
{
    bind="${2:-0.0.0.0}:${1:-8080}"
    echo
    echo Open "\"http://$bind/toc.php\"" in browser.
    echo
    php -S "$bind" -t .
}

__hcBuildVariant__()
{
    variant="$1"

    mkdir -p build/"$variant" build/"$variant"/src-include

    cp src-include/*.css build/"$variant"/src-include

    [ "$HARDCOPY_NOGPL" ] ||
        cp -R src-include/gnu-freefont build/"$variant"/src-include

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

    HARDCOPY_OUTPUT_CONTROL=/ php toc.php | (
        export HARDCOPY_OUTPUT_CONTROL=.
        php toc.php > build/multipage/toc.html
        while read page ; do
            export HARDCOPY_OUTPUT_CONTROL="$page"
            php toc.php > build/multipage/"$page".html
        done
    )

    HARDCOPY_OUTPUT_CONTROL=./ php toc.php > build/multipage/frame.html
}

hcBuildSinglepage()
{
    __hcBuildVariant__ singlepage

    HARDCOPY_OUTPUT_CONTROL="" php toc.php > build/singlepage/main.html
}
