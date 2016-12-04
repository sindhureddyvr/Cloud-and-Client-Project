#!/bin/bash

parameters=$#

if [ $# -ne 8 ]
then
echo "This script requires 8 parameters to be passed(image-id,key-name,security-group-id,launch-configuration,count,Iam-Profile-Name,load-balancer-name,auto-scaling-group) respectivly.Please pass the right number of parameters and run the script again."
else
echo -e "Creating 3 micro instances \e[0m"
aws ec2 run-instances --image-id $1 --key-name $2 --security-group-ids $3 --instance-type t2.micro --user-data file://install-app.sh --count $5 --placement AvailabilityZone=us-west-2a --iam-instance-profile Name="$6"
echo -e "New Instances are created"
sleep 15
echo -e "\e[1mWait untill the Instance are in  Running State\e[0m"
instance_id=$(aws ec2 describe-instances --query 'Reservations[].Instances[].[InstanceId]' --filters Name=instance-state-name,Values=pending)
echo $instance_id
aws ec2 wait instance-running --instance-ids $instance_id
echo -e "Wait is completed and instances are now in Running state"
sleep 15
echo -e "\e[1mCreating a Load Balancer\e[0m"
aws elb create-load-balancer --load-balancer-name $7 --listeners Protocol=Http,LoadBalancerPort=80,InstanceProtocol=Http,InstancePort=80 --subnets subnet-1865246e
echo -e "Load balancer is  created successfully"

echo -e "\e[1mRegistering instances with the load balancer \e[0m"
aws elb register-instances-with-load-balancer --load-balancer-name $7 --instances $instance_id
echo -e "Instances are registered to load balancer successfully"

echo -e "\e[1mCreating Autoscaling Launch Configuration\e[0m"
aws autoscaling create-launch-configuration --launch-configuration-name $4 --image-id $1 --key-name $2 --instance-type t2.micro --user-data file://install-app.sh
echo -e "Autoscaling Launch Configuration created successfully"

echo -e "\e[1mCreating Autoscaling Group\e[0m"
aws autoscaling create-auto-scaling-group --auto-scaling-group-name $8 --launch-configuration $4 --availability-zone us-west-2a --max-size 5 --min-size 0 --desired-capacity 1
echo -e "AutoScaling Group Created Successfully"

echo -e "\e[1mAttaching created instances to auto scaling group \e[0m"
aws autoscaling attach-instances --instance-ids $instance_id --auto-scaling-group-name $8
echo -e "Instances attached to auto-scaling-group successfully"

echo -e "\e[1mAttaching load balancer to auto scaling group\e[0m"
aws autoscaling attach-load-balancers --auto-scaling-group-name $8 --load-balancer-names $7
echo -e "Load balancer attached to auto-scaling-group successfully"

echo -e "\e[Launcing an instance to run crontab"
aws ec2 run-instances --image-id $1 --key-name $2 --security-group-ids $3 --instance-type t2.micro --user-data file://cronjob.sh --count $5 --placement AvailabilityZone=us-west-2a --iam-instance-profile Name="$6"


fi
