# 3971thesis
## What is this?
This is a web application of sorts that I am building to help me with my honours thesis. As at time of writing (March 2015), its primary capability is storing a relational database of journal articles for Literature Review and:

### 1) Analysing the citations between journal articles
In the screenshot below, you see a directed graph where journal articles point upwards to those that they cite. Each article is represented by a circular node whose radius is proportional to number of instances where the article has been cited. Therefore this graph helps you identify state of the art (i.e., the journal articles which sink to the bottom), and seminal works (i.e., the journal articles which bubble to the top and/or those which have the largest size).

![Screenshot](https://raw.githubusercontent.com/blairw/3971thesis/master/misc/graph-ss.png?v=2)

Thanks to the magic of sigmajs, you can click on a node to highlight just that node and its neighbours (immediate citers and citees), to get a clear view of the literature contribution of a particular paper.

![Screenshot](https://raw.githubusercontent.com/blairw/3971thesis/master/misc/graph-filtered-ss.png?v=2)

### 2) Assisting with literature coding
In the screenshot below, you see a table which tracks the process of essentially filling out a form for each journal article and what kind of methodology it uses, is it relevant to the topic we are researching, etc.

![Screenshot](https://raw.githubusercontent.com/blairw/3971thesis/master/misc/lit-coding-ss.png?v=2)

We also graph this as a line graph to motivate you to work harder :)
![Screenshot](https://raw.githubusercontent.com/blairw/3971thesis/master/misc/lit-coding-graph-ss.png?v=3)

## How do I install this?
You will need to have mySQL server and PHP-capable webserver (e.g. Apache) installed on your system. Then you will need to clone 3971thesis into a directory in your webserver public HTML directory and set up two other directories alongside 3971thesis:

- 3971thesis
- 3971thesis-db
  - db-MysqlAccess.php (creates $mysqli, a new connection to your mysql database)
- 3971thesis-files
  - 1.pdf
  - 2.pdf
  - 3.pdf
  - (...)
  - (any other downloaded journal articles you may have)
  - (they just need to have the name of the article_id)
  - (i.e., files need to be named in the format, *article_id*.pdf)

You'll also need to set up the database in mySQL. Unfortunately as at time of writing I don't really have much time to make this user-friendly and this is still very much a work in progress, so for the time being, your best bet is to look through the mySQL queries in the PHP files to derive the data structure :) sorry!

There's also not much for creating or updating data - I'm using a mySQL management tool (HeidiSQL) to do it. You might want to use something similar or set up phpMyAdmin.

![Screenshot](https://raw.githubusercontent.com/blairw/3971thesis/master/misc/heidisql-ss.png)

## Credits
- *[jQuery](https://jquery.org/)*, by jQuery Foundation, [MIT License](https://jquery.org/license/)
- *[Bootstrap](http://getbootstrap.com/)*, by Bootstrap Core Team, Contributors and Twitter, Inc, [MIT License](https://github.com/twbs/bootstrap/blob/master/LICENSE)
- *[Select2](https://select2.github.io/)*, by Kevin Brown and Igor Vaynberg, and Select2 contributors, [MIT License](https://github.com/select2/select2/blob/master/LICENSE.md)
- *[chartjs](http://www.chartjs.org/)*, by Nick Downie, [MIT License](http://www.chartjs.org/docs/#notes-license)
- *[sigmajs](http://sigmajs.org/)*, by Alexis Jacomy, [Copyright (C) 2013-2014 but use and distribution permitted](https://github.com/jacomyal/sigma.js/blob/master/LICENSE.txt)
- *[dagre](https://github.com/cpettitt/dagre)*, by Chris Pettitt, [MIT License](https://github.com/cpettitt/dagre/blob/master/LICENSE)
- *[Font-Awesome](http://fortawesome.github.io/Font-Awesome/)*, by Dave Gandy, [MIT License](https://github.com/FortAwesome/Font-Awesome#license)
