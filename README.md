# YardProProject
## WebApp Name: 
YardPro
## WebApp Description: 
YardPro is a full-stack web application designed to manage customer bookings, service scheduling, and internal employee routing for a modern lawn care business. It solves the inefficiency of manual scheduling and communication by providing distinct interfaces for Customers and Admin/Management. 
Customers can quickly request and track services, while Admins can manage the service catalog, assign tasks to employees, and track billing status, ensuring an efficient "green route" for all services

## WebApp Functions & Pages:
•	Functionalities (what the WebApp does)
All Users role, Registration, Login/Logout, Update Profile, Delete Account. 
Pages for users registrations: index.php, login.php, register.php, profile.php
For Customer role, they can do create, read, update and delete operations on their own Service Requests. View Invoices (Read-only). 
Pages for Customer role: dashboard.php, request.php, my_services.php, my_invoices.php
For Admin Role, CRUD on Service Types, Employee Records. View/Assign all Service Requests. View all Invoices.
Pages for admin role: admin_requests.php, admin_services.php, admin_employees.php

## all data elements
 
#	Table Name	Data Elements (Fields)	Relationships
1	Users	user_id (PK), email (UNIQUE), password_hash, is_admin (0/1), address, phone, session_count, last_login.	Master table for login credentials and roles.
2	Service Types	service_type_id (PK), name, base_price, description.	Admin-managed catalog of services offered.
3	Employees	employee\_id (PK), user\_id (FK to Users.user_id), 
pay\_rate, hire\_date.	1:1 relationship with the Users table for employee-specific data.
4	Service_Requests	request\_id (PK), customer\_id (FK to Users.user_id), 
type\_id (FK to Service_Types.service_type_id), 
employee\_id (FK to Employees.employee_id), schedule\_date, status (Pending/Scheduled/Completed).	Core data entity—requires complex JOINs to display information.
5	Invoices	invoice\_id (PK), request\_id (FK to Service_Requests.request_id), amount\_due, date\_sent, paid\_status.	Billing data linked directly to a completed service request.

The admin dashboard will execute a complex query over 4 tables to view the schedule: It will join Service_Requests to display the date and status, Users (via customer_id) to show the customer's name, Service_Types to show the service name, and Employees (via employee_id) to show who is assigned the task. -  Required JOIN Query


<img width="468" height="616" alt="image" src="https://github.com/user-attachments/assets/13b89803-7241-47cc-a0ba-1c131d165777" />
