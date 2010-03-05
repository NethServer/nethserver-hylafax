#!/bin/sh
#
# simple mail-to-fax utility using both faxmail and sendfax.
#
# - Lee Howard
#

#
# Mail addresses can be three ways:
#	1) user@host.org
#	2) "User Name" <user@host.org>
#	3) user@host.org (User Name)
#
# in the latter cases, quotes may or may not be used,
# there may or may not be any User Name at all, and User Name
# may come before or after user@host.org.
#
# We need to make sure we handle all these possibilities for
# both TO and FROM situations.  Return-Path is different.
#

RANDOMFAX=/tmp/mail2fax.$$
mkdir $RANDOMFAX

#avoid mail header printing
echo "X-FAX-Headers: clear" >> $RANDOMFAX/_message_
cat >> $RANDOMFAX/_message_

# Uncomment the following three lines for debugging.
#set -x
#exec > /tmp/mail2faxlog.$$ 2>&1
#cp $RANDOMFAX/_message_ /tmp/mail2faxmail.$$

TOLINE=`grep -e "^subject:" -i $RANDOMFAX/_message_ | sed q`
FROMLINE=`grep -e "^from:" -i $RANDOMFAX/_message_ | sed q`
TONUMBER=`echo $TOLINE| cut -d':' -f2`

if [ "`echo $FROMLINE | grep '<.*>'`" != "" ]; then
        FROMPATH=`echo $FROMLINE| sed -e 's/.*<\(.*\).*>.*/\1/'`
else
        FROMPATH=`echo $FROMLINE| sed -e 's/^[Ff]rom://g' -e 's/[ ]*\([^ ]*\).*/\1/'`
fi

cat $RANDOMFAX/_message_ | faxmail -v -T $FROMPATH | sendfax -vv -n -D -f "$FROMPATH" -d $TONUMBER
[ ! $? -eq 0 ] && exit 100

rm -rf $RANDOMFAX

exit 0

