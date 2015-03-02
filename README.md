# 3971thesis
## What is this?
This is a mySQL + PHP + JavaScript + jQuery + Bootstrap + Select2 + sigmajs + dagre tool that I am building to help me with my honours thesis. As at time of writing (March 2015), its primary capability is storing a relational database of journal articles for Literature Review and analysing the citations between them.

![Screenshot](https://raw.githubusercontent.com/blairw/3971thesis/master/misc/graph-ss.png)

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

You'll also need to set up the database in mySQL. Unfortunately as at time of writing I don't really have much time to make this user-friendly and this is still very much a work in progress, so for the time being, your best bet is to look through the mySQL queries in the PHP files to derive the data structure :) sorry!
