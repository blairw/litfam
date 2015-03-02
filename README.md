# 3971thesis
This is a mySQL + PHP + JavaScript + jQuery + Bootstrap + Select2 + sigmajs + dagre tool that I am building to help me with my honours thesis. As at time of writing (March 2015), its primary capability is storing a relational database of journal articles for Literature Review and analysing the citations between them.

![Screenshot](https://raw.githubusercontent.com/blairw/3971thesis/master/misc/graph-ss.png)

In order for the scripts to work you will need to clone 3971thesis into a directory and set up two other directories alongside it:
- 3971thesis
- 3971thesis-db
  - db-MysqlAccess.php (creates $mysqli, a new connection to your mysql database)
- 3971thesis-files
  - 1.pdf
  - 2.pdf
  - 3.pdf
  - (...)
  - (any other downloaded journal articles you may have).
