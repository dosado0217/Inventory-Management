# Simple Inventory Management System

A full-stack inventory management system built with Laravel 11 (API), React.js 18 (Frontend), and Docker.

==========================
 Features
==========================
- User Authentication (Register / Login)
- Category, Product, Supplier CRUD
- Dockerized Environment

==========================
 Tech Stack
==========================
Backend     : Laravel 11  
Frontend    : React.js 18 + MUI  
Database    : MySQL
DevOps      : Docker + Docker Compose  

==========================
Download the Frontend Source Code
==========================

1. Click or copy and paste the link below into your browser and download the simple-inventory-management-frontend

https://drive.google.com/drive/folders/1ldbHZuOPEBBBq70S9_bF0FTQyKle1fpz?usp=sharing

2. Put the downloaded zip folder in the root directory of the project like this:

  simple-inventory-management/
├── simple-inventory-management-api/
├── simple-inventory-management-frontend.zip
└── docker-compose.yml

3. Extract the zip file so it looks like:

simple-inventory-management/
├── simple-inventory-management-api/
├── simple-inventory-management-frontend/
└── docker-compose.yml

==========================
 Docker Setup
==========================

1. Build & Run Containers
--------------------------------
docker-compose build --no-cache  
docker-compose up -d

==========================
 URLs
==========================
Frontend    : http://localhost:3000  
Backend API : http://localhost:8000