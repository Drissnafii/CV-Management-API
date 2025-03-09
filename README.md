# Laravel Job Application API - MVP

A RESTful API for job applications built with Laravel, focusing on core functionality for a minimum viable product.

## Core Features

- **User Authentication**
  - Registration and login with JWT/Sanctum tokens
  
- **Job Offers**
  - CRUD operations for job listings
  - Basic job details and browsing
  
- **CV Management**
  - Upload CV files (PDF/DOCX)
  - Local storage implementation
  
- **Applications**
  - Apply to job offers with uploaded CVs
  - Basic email confirmations

## Tech Stack

- Laravel 12
- MySQL
- Laravel Sanctum
- Queue system for emails

## API Endpoints

### Authentication
- `POST /api/register` - Create new user account
- `POST /api/login` - Get authentication token

### Job Offers
- `GET /api/job-offers` - List all job offers
- `POST /api/job-offers` - Create job offer
- `GET /api/job-offers/{id}` - View specific job offer
- `PUT /api/job-offers/{id}` - Update job offer
- `DELETE /api/job-offers/{id}` - Remove job offer

### CVs
- `POST /api/cvs` - Upload CV file
- `GET /api/cvs` - List user's CVs

### Applications
- `POST /api/applications` - Apply to job offer with CV
- `GET /api/applications` - List user's applications

## Next Steps

Future enhancements after MVP:
- Advanced filtering for job offers
- Multi-application functionality
- CV analysis and summary generation
- Periodic CSV exports for recruiters