# Personalized Video Campaign Manager

## Overview
This project is a Laravel-based API system for managing personalized video campaigns. It allows clients to create campaigns and asynchronously upload user-specific video data.

The system is designed to be scalable, efficient, and API-driven, with background processing handled via Laravel Queues.

---

## Tech Stack

- PHP 8.x
- Laravel 12+
- MySQL
- Docker & Docker Compose
- Laravel Queues

---

## Features

### Campaign Management
- Create campaigns linked to clients
- Store campaign start and end dates

### Campaign Data Processing
- Bulk upload of campaign data via API
- Supports dynamic `custom_fields` using JSON storage
- Handles large datasets efficiently

### Asynchronous Processing
- Campaign data ingestion is handled via background jobs
- API returns immediately with HTTP 202

### Duplicate Handling Strategy
If a `user_id` already exists within a campaign:
- The existing record is updated
- Custom fields are merged
- A log entry is created in `campaign_data_duplicate_logs`

Each duplicate attempt stores:
- Previous values
- Updated values
- Action taken

### Analytics Command
Provides campaign insights via CLI:

```bash
php artisan analytics:campaign {campaignId}
