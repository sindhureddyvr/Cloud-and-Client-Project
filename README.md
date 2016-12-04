# sreddy7
ITMO-544

a)	Execute install-app-env.sh - One time installation

It takes 2 positional parameters raw-svr, raw-uay
     
b)	Execute install-env.sh - Make sure you have cronjob.sh and install-app.sh in same folder

It takes 8 positional parameters  ami-id, key-name, security-group, launch-configuration, count, iam-profile-name,load-balancer-name, auto-scaling-group
ami-9469cff4 awskey sg-d5539bac webserver 1 developer itmo-544 webserverdemo

c)	Index.php:

usernames: sreddy7@hawk.iit.edu /
           hajek@iit.edu /
           controller
           
Password for all the logins : password

d) Login as controller(admin)
   1)Upload a .jpg image (Do not upload .png)
   2)Go to gallery  and check if it is uploaded.
   3)Turn off the upload feature
   4)Backup the database
   5)Delete a row in the database for testing and then click restore DB link
   6)Database is restored.
   4)Logout
 
e) Login as any other users
   1)You wont be seeing any upload link
   2)Turn the upload feature on by logging in as an admin.
   3)You can now see upload button. Upload a .jpg image. 
   4)In the gallery you can see only those images uploaded by you.
   5)Logout
   
 Meanwhile a job will running in the background and keeps polling and once it finds .jpg image and IIT logo water mark is     placed.You can verify this by going to raw-uay bucket or you can check in the database finished url and status is changed.  
 
 f) Run destroy script
