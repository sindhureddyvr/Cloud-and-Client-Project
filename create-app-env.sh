#!/bin/bash

echo "Creating MYSQL DB instance"
aws rds create-db-instance --db-instance-identifier mysqldbinstance --db-instance-class db.m1.small --engine MySQL --master-username masterawsuser --master-user-password masteruserpassword --a
llocated-storage 20 --backup-retention-period 3
echo “Waiting until the instance is available”
aws rds wait db-instance-available --db-instance-identifier mysqldbinstance
echo “MYSQL DB instance created"
