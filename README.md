CODEXQL
=======


TPF was an open source forum built on PHP, but the biggest issue was the way in which data was stored. 
It was stored using flat files, almost everything from the forum hierarchy of categories, 
topics and posts to aggregated data such as view count etc. was stored in files.
So I invented the CODEXQL which used minimal flat files for storage but had the data stored in a logical way 
such as in the form of database(folder),tables(.cxt codexql table) and columns(.cxf codexql field). 
CODEXQL understood a subset of SQL which made querying data a breeze rather than 
opening multiple files(I/O bottleneck) and searching for data.  

[CODEXQL was written in 2009 and is not in development anymore :(  
Because the software(TPF) it was built to be used with is not developed anymore. 

original TPF: http://www.buddhamind.info/aid/forum/index.php (I hope this address exists)


Note: This project is used only to store my old code for reference and codex zoyus is a dummy company name that I made up :P
