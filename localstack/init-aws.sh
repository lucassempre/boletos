#!/bin/bash
aws configure set aws_access_key_id default_access_key --profile=localstack
aws configure set aws_secret_access_key default_secret_key --profile=localstack
aws configure set region sa-east-1 --profile=localstack

aws configure list --profile=localstack

awslocal s3api create-bucket --bucket files
