
# Team-Task-Manager-Project

## Project Description
This is an ongoing project aimed at developing a task management application for teams. It will allow users to create projects, manage tasks, and collaborate on various team-related activities. Features will include project creation, task assignment, and role-based access.

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
## Development

This project is under active development. Features are being added progressively, and it is not yet a fully functional task management solution.
