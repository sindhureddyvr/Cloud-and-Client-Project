echo -e "\e[1mLab5 Destroy script\e[0m"

echo -e "Collect instance id's attached to the auto-scaling-group"
instance_id=$(aws autoscaling describe-auto-scaling-instances --query 'AutoScalingInstances[*].[InstanceId]')

#instance_id=$(aws ec2 describe-instances --query 'Reservations[].Instances[].[InstanceId]' --filters Name=instance-state-name,Values=running)

echo -e "Collect auto-scaling-group-name"
autoscalingGrpName=$(aws autoscaling describe-auto-scaling-groups --query 'AutoScalingGroups[*].[AutoScalingGroupName]')

echo -e "Collect launch-configuration-name"
launchConfigName=$(aws autoscaling describe-launch-configurations --query 'LaunchConfigurations[*].[LaunchConfigurationName]')

echo -e "Collect load-balancer-name"
loadBalancerName=$(aws elb describe-load-balancers --query 'LoadBalancerDescriptions[*].[LoadBalancerName]')

echo -e "\e[1mSet desired capacity to 0 \e[0m"
aws autoscaling set-desired-capacity --auto-scaling-group-name $autoscalingGrpName --desired-capacity 0
echo -e "Desired Capacity is set to 0"

croninstance=$(aws ec2 describe-instances --query 'Reservations[].Instances[].[InstanceId]' --filters Name=instance-state-name,Values=running)

aws ec2 terminate-instances --instance-ids $croninstance

echo -e "\e[1mWait until the instances are terminated\e[0m"
aws ec2 wait instance-terminated --instance-ids $instance_id
echo -e "Wait is completed and instances in Running state are terminated"

sleep 15

echo -e "\e[1mDeleting Auto-scaling-group \e[0m"
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $autoscalingGrpName
echo -e "AutoScaling group-name deleted"

echo -e "\e[1mDeleting Launch-configuration-group \e[0m"
aws autoscaling delete-launch-configuration --launch-configuration-name $launchConfigName
echo -e "Launch configuration deleted"

echo -e "\e[1mDeleting Load-balancer \e[0m"
aws elb delete-load-balancer --load-balancer-name $loadBalancerName
echo -e "Load-balancer deleted"

#Delete database
aws rds delete-db-instance --db-instance-identifier sreddy7-readreplica --skip-final-snapshot

echo "Waiting for database to be terminated"

aws rds wait db-instance-deleted --db-instance-identifier sreddy7-readreplica

aws rds delete-db-instance --db-instance-identifier sreddy7 --skip-final-snapshot

aws rds wait db-instance-deleted --db-instance-identifier sreddy7

#delete s3 bucket
aws s3 rb s3://raw-svr --force
aws s3 rb s3://raw-uay --force

#delete sns topic
ARN=`aws sns list-topics --query 'Topics[*]'.'TopicArn' | cut -d\" -f2`
aws sns delete-topic --topic-arn $ARN

#delete sqs queue
URL=`aws sqs get-queue-url --queue-name sreddy7 --query 'QueueUrl' | cut -d\" -f2`
aws sqs delete-queue --queue-url $URL

croninstance=$(aws ec2 describe-instances --query 'Reservations[].Instances[].[InstanceId]' --filters Name=instance-state-name,Values=running)

aws ec2 terminate-instances --instance-ids $croninstance

