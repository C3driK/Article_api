# Article_api
When running EKS, it requires specific networking. Because all environments will most likely be different, there's a CloudFormation template for this exact purpose.

---

To create your cluster VPC with public and private subnets

1. Open the AWS CloudFormation console at https://console.aws.amazon.com/cloudformation.

2. From the navigation bar, select a Region that supports Amazon EKS.

3. Choose Create stack, With new resources (standard).

4. For Choose a template, select Specify an Amazon S3 template URL.

5. This will serve:
```
https://s3.us-west-2.amazonaws.com/amazon-eks/cloudformation/2020-10-29/amazon-eks-vpc-private-subnets.yaml
```

6. On the *Specify Details* page, fill out the following:

- Stack name: Choose a stack name for your AWS CloudFormation stack. For example, you can call it eks-vpc.

- VpcBlock: Choose a CIDR range for your VPC. Each worker node, pod, and load balancer that you deploy is assigned an IP address from this block. The default value provides enough IP addresses for most implementations, but if it doesn't, then you can change it. For more information, see VPC and subnet sizing in the Amazon VPC User Guide. You can also add additional CIDR blocks to the VPC once it's created.

- PublicSubnet01Block: Specify a CIDR block for public subnet 1. The default value provides enough IP addresses for most implementations, but if it doesn't, then you can change it

- PublicSubnet02Block: Specify a CIDR block for public subnet 2. The default value provides enough IP addresses for most implementations, but if it doesn't, then you can change it

- PrivateSubnet01Block: Specify a CIDR block for private subnet 1. The default value provides enough IP addresses for most implementations, but if it doesn't, then you can change it

- PrivateSubnet02Block: Specify a CIDR block for private subnet 2. The default value provides enough IP addresses for most implementations, but if it doesn't, then you can change it

7. (Optional) On the Options page, tag your stack resources. Choose Next.

8. On the Review page, choose Create.

9. When your stack is created, select it in the console and choose Outputs.

10. Record the SecurityGroups value for the security group that was created. When you add nodes to your cluster, you must specify the ID of the security group. The security group is applied to the elastic network interfaces that are created by Amazon EKS in your subnets that allows the control plane to communicate with your nodes. These network interfaces have Amazon EKS <cluster name> in their description.

11. Record the VpcId for the VPC that was created. You need this when you launch your node group template.

12. Record the SubnetIds for the subnets that were created and whether you created them as public or private subnets. When you add nodes to your cluster, you must specify the IDs of the subnets that you want to launch the nodes into.




# Create an S3 bucket to store Terraform state files

## Create The Terraform Configurations

1. You can find the Terraform configuration for the S3 bucket in the /Terraform/s3.statefile folder, The Terraform configuration files are used to create an S3 bucket that will store your TFSTATE.

The Terraform `main.tf` will do a few things:
- Create the S3 bucket
- Ensure that version enabling is set to `True`
- Utilize AES256 encryption 

2. Create the bucket by running the following:
- `terraform init` - To initialize the working directory and pull down the provider
- `terraform plan` - To go through a "check" and confirm the configurations are valid
- `terraform apply - To create the resource


# Create an Elastic Container Registry Repository
## Create the ECR Terraform Configuration

1. You can find the Terraform configuration for ECR in /Terraform/ecr folder. The Terraform configuration files are used to create a repository in Elastic Container Repository (ECR). 

The Terraform `main.tf` will do a few things:
- Use a Terraform backend to store the `.tfstate` in an S3 bucket
- Use the `aws_ecr_repository` Terraform resource to create a new respository. 

2. Create the bucket by running the following:
- `terraform init` - To initialize the working directory and pull down the provider
- `terraform plan` - To go through a "check" and confirm the configurations are valid
- `terraform apply - To create the resource


# Create An EKS Cluster and IAM Role/Policy

## Create the EKS Terraform Configuration

1. You can find the Terraform configuration for EKS in the Terraform/eks_iam file. The Terraform configuration files are used to create an EKS cluster and IAM Role/Policy for EKS. 

The Terraform `main.tf` will do the following:
- Use a Terraform backend to store the `.tfstate` in an S3 bucket
- Use the `aws_iam_role` and `aws_iam_policy` Terraform resource to create a new IAM configuration. 

2. Create the bucket by running the following:
- `terraform init` - To initialize the working directory and pull down the provider
- `terraform plan` - To go through a "check" and confirm the configurations are valid
- `terraform apply - To create the resource


# Creating the Docker image for the Uber app

In this lab you will create a Docker image to containerize the Uber app.

## Create The Docker Image

1. The Dockerfile is already created containing all the required dependencies, use "docker build" to build the image, and "docker run" to run the image in a  docker container locally.
2. To confirm the Docker container is running, run the following command:
`docker container ls`

You should now see the container running.



# Push Image To ECR

The ECR repo will be where you store the Docker image that you created on your localhost

## Log Into The ECR Repository
1. Terraform Code
2. Log in to ECR with AWS CLI
`aws ecr get-login-password --region *your_aws_region* | docker login --username AWS --password-stdin *your_aws_account_id*.dkr.ecr.*your_aws_region*.amazonaws.com`


## Tag The Docker image
1. Tag the Docker image
`docker tag uber *your_aws_account_id*.dkr.ecr.*your_aws_region*.amazonaws.com`

## Push The Docker Image To ECR
1. Push the Docker image to ECR
`docker push *your_aws_account_id*.dkr.ecr.us-east-1.amazonaws.com/*repo_name*`


# Connecting To Elastic Kubernetes Service (EKS)

When you're deploying locally, without any CI/CD to EKS, you'll need to authenticate from your local terminal.

Once you authenticate to EKS from your local terminal, a `kubeconfig` gets stored on your computer. The `kubeconfig` has all of the connection information and authentication needs to connect to EKS.

## Connecting To EKS

1. Run the following command to connect to EKS:
`aws eks --region *your_aws_region* update-kubeconfig --name *your_eks_cluster_name`

2. Once connected, you should be able to run commands like the following to confirm you're connected:
`kubectl get nodes`



# Create The Kubernetes Manifest

At this point the Docker image has been succesfully created.

Now it's time to set up the Kubernetes manifest, which will take the application and deploy it to EKS.

## The Manifest

The Kubernetes manifest will consist of two components:
- The deployment
- The service

The deployment is what gets the application running in Kubernetes

The service is what exposes the Kubernetes application so you can, for example, reach the frontend from a load balancer hostname or IP.

The manifest can be found in the `kubernetes_manifest` directory. 
