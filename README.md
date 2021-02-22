# The project is a CRUD that can write into database a lectures, students and grades. Table with grades should be connected to studens and lectureres.

**The database should have four tables.**
| Lectures| |                            
| :---: | :---: | 
| id | int(11)| 
| name | varcahr(64)| 
| description | text|   

| Students| |                            
| :---: | :---: | 
| id | int(11)| 
| name| varcahr(64)| 
| surname| varcahr(64)|   
| email| varcahr(64)|   
| phone| varcahr(32)|   

| Grades| |                            
| :---: | :---: | 
| id | int(11)| 
| student_id | int(11) |   
| lectur_id| int(11) |   
| grade | int(11)| 
|***The connection between tables*** | **grades.mechanic_id *------> student.id***|
| | **grades.mechanic_id *------> lectur.id***|

| Users| |                            
| :---: | :---: | 
| id | int(11)| 
| email| varcahr(64)| 
| pass| password(128)|   

**The project has several specifications:**
- add, deleate and modify data from database. 
- grades have a fields (drop down) from which it is possible to select a lecturs and students
- sorting grade by lowest and highest grade 
- sorting students by name and surname
- sorting lectur by name 
- sign up, sign in and sign out funtionality
- validation of inserted data
- student and lectur cannot be deleated if there is a grade for student or lectur
- grade cannot be created if lecturs and students are not created
- lectur description filed made with WYSIWYG type redactor
- responsive design of project
- protection agains SQL injections


### project was made with Symfony framework
