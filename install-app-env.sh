#!/bin/bash

parameters=$#

if [ $# -ne 2 ]

then

echo "This script requires 2 bucket names to be passed as raw-svr and raw-uay respectivly.Please pass the right number of parameters and run the script again."
else

echo "Creating MYSQL DB instance"

aws rds create-db-instance --db-instance-identifier sreddy7 --db-instance-class db.t1.micro --engine mysql --master-username sreddy7 --master-user-password sreddy123 --allocated-storage 20 --db-name school

echo "Waiting until the instance is available"

aws rds wait db-instance-available --db-instance-identifier sreddy7

echo "MYSQL DB instance created"

aws rds create-db-instance-read-replica --db-instance-identifier sreddy7-readreplica --source-db-instance-identifier sreddy7 

echo "Creating SNS Topic"

aws sns create-topic --name sreddy7

var =$(aws sns list-topics | cut -f 2)

aws sns subscribe --topic-arn $var --protocol sms --notification-endpoint 1-312-561-8112

echo "Creating SQS Queue"

aws sqs create-queue --queue-name sreddy7

echo "Creating a Bucket"

aws s3 mb s3://$1 --region us-west-2

aws s3 mb s3://$2 --region us-west-2

fi

