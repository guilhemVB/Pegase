for file in C:/wamp/www/Pegase/web/images/banners/countries/*
do
	name="`basename $file .jpg`"
	git mv -f $name.jpg $name.JPG
done