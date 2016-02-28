dnl $Id$
dnl config.m4 for extension datetolunar

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary. This file will not work
dnl without editing.

dnl If your extension references something external, use with:

PHP_ARG_WITH(datetolunar, for datetolunar support,
dnl Make sure that the comment is aligned:
[  --with-datetolunar             Include datetolunar support])

dnl Otherwise use enable:

PHP_ARG_ENABLE(datetolunar, whether to enable datetolunar support,
dnl Make sure that the comment is aligned:
[  --enable-datetolunar           Enable datetolunar support])

if test "$PHP_DATETOLUNAR" != "no"; then
  dnl Write more examples of tests here...

  dnl # --with-datetolunar -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/datetolunar.h"  # you most likely want to change this
  dnl if test -r $PHP_DATETOLUNAR/$SEARCH_FOR; then # path given as parameter
  dnl   DATETOLUNAR_DIR=$PHP_DATETOLUNAR
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for datetolunar files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       DATETOLUNAR_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$DATETOLUNAR_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the datetolunar distribution])
  dnl fi

  dnl # --with-datetolunar -> add include path
  dnl PHP_ADD_INCLUDE($DATETOLUNAR_DIR/include)

  dnl # --with-datetolunar -> check for lib and symbol presence
  dnl LIBNAME=datetolunar # you may want to change this
  dnl LIBSYMBOL=datetolunar # you most likely want to change this 

  dnl PHP_CHECK_LIBRARY($LIBNAME,$LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $DATETOLUNAR_DIR/$PHP_LIBDIR, DATETOLUNAR_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_DATETOLUNARLIB,1,[ ])
  dnl ],[
  dnl   AC_MSG_ERROR([wrong datetolunar lib version or lib not found])
  dnl ],[
  dnl   -L$DATETOLUNAR_DIR/$PHP_LIBDIR -lm
  dnl ])
  dnl
  dnl PHP_SUBST(DATETOLUNAR_SHARED_LIBADD)

  PHP_NEW_EXTENSION(datetolunar, datetolunar.c, $ext_shared)
fi
