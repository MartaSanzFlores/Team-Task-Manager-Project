﻿# Team Task Manager Project

## Project Description
This project is a **task management application for teams**, allowing users to create projects, manage tasks, assign members to projects, and designate task owners. It's designed to improve team collaboration and streamline task tracking. 

## Functional Overview

- **Project Creation**  
  Users can create and manage projects with customizable fields such as project name, description, start date, and deadlines.

- **Task Management**  
  Tasks can be created, assigned to different members with specific roles, and tracked with deadlines and statuses.

- **Team Member Management**  
  Add and assign team members to projects with specific roles, including task responsibility.

- **Role-Based Access**  
  Users are assigned roles (admin, project manager, member) that determine their permissions.

- **User Authentication**  
  Authentication system is in place for user login and registration.

- **User Account Management**  
  Users can edit their profiles, change their passwords, and update their email addresses and image profile.

## Technologies Used

- **Framework**: Symfony 7 (tested with PHP 8.2)
- **Templating**: Twig
- **ORM**: Doctrine
- **Database**: MySQL
- **User Profile Management**: Symfony's form handling and file upload features  
- **Styling**: Bootstrap
- **Security**: Session-based authentication, Role-based access control
- **Development Tools**:  
  - **Docker**: For containerizing the application  
  - **Docker Compose**: For managing multi-container applications  
- **Testing**:  
  - **PHPUnit**: For running unit tests   

## Project Demo
Click below to watch the project demo:

[![Project Demo Thumbnail](https://github.com/MartaSanzFlores/Team-Task-Manager-Project/blob/main/demo/demo.png)](https://youtu.be/bWP89J53Evs)

## Routes

Here is the list of available API routes:

- **GET /api/calendar-events** - Fetch calendar events.
- **POST /project/api/update-task-status/task-{id}** - Update the status of a task.
- **POST /project/api/update-task-progressState/task-{id}** - Update the progress state of a task.
- **POST /project/api/update-task/{taskId}** - Update task details.
- **POST /api/profile/upload** - Upload a user profile image.
- **GET /dashboard** - User dashboard.
- **POST /login** - User login.
- **POST /create-project** - Create a new project.
- **POST /edit-project/{id}** - Edit an existing project.
- **POST /delete-project/{id}** - Delete a project.
- **GET /project/{id}** - Get details of a project.
- **POST /register** - Register a new user.
- **POST /project/{id}/create-task** - Create a task for a specific project.
- **GET /profile** - View user profile.
- **GET /my-projects** - View user’s projects.
- **GET /logout** - Logout from the application.

## Requirements
- Docker
- Docker Compose
- Composer

## Setup Instructions

1. **Clone the repository**

    ```bash
    git clone https://github.com/MartaSanzFlores/Team-Task-Manager-Project.git
    cd team-task-manager-project
    ```

2. **Build the containers**

    Build the application containers using Docker Compose:

    ```bash
    docker-compose up --build
    ```

3. **Install dependencies**

    Install PHP dependencies with Composer:

    ```bash
    docker-compose exec php composer install
    ```

4. **Run database migrations**

    Run the database migrations to set up the schema:

    ```bash
    docker-compose exec php php bin/console doctrine:migrations:migrate
    ```
5. **Run compilations**

    ```bash
    docker-compose exec php php bin/console asset-map:compile
    npm install

    ```

## Unit Tests

### Install Database for Testing

    ```bash
    docker-compose exec php php bin/console --env=test doctrine:database:create
    docker-compose exec php php bin/console --env=test doctrine:schema:create 
    ```

### Run PHPUnit Tests

    ```bash
    docker-compose exec php ./vendor/bin/phpunit tests
    ```
