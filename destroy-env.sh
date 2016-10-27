#!/bin/bash

echo -e "\e[1mLab5 Destroy script\e[0m"

echo -e "Collect instance id's attached to the auto-scaling-group"
instance_id=$(aws autoscaling describe-auto-scaling-instances --query 'AutoScalingInstances[*].[InstanceId]')

echo -e "\e[1mSet desired capacity to 0 \e[0m"
aws autoscaling set-desired-capacity --auto-scaling-group-name webserverdemo --desired-capacity 0
echo -e "Desired Capacity is set to 0"

echo -e "\e[1mWait until the instances are terminated\e[0m"
aws ec2 wait instance-terminated --instance-ids $instance_id
echo -e "Wait is completed and instances in Running state are terminated"

sleep 15

echo -e "\e[1mDeleting Auto-scaling-group \e[0m"
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name webserverdemo
echo -e "AutoScaling group-name deleted"

echo -e "\e[1mDeleting Launch-configuration-group \e[0m"
aws autoscaling delete-launch-configuration --launch-configuration-name webserver
echo -e "Launch configuration deleted"

echo -e "\e[1mDeleting Load-balancer \e[0m"
aws elb delete-load-balancer --load-balancer-name itmo-544
echo -e "Load-balancer deleted"