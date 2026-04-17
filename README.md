# Personalized Video Campaign Manager

## Overview

This project is a Laravel-based API system for managing personalized video campaigns.
It allows clients to create campaigns and upload user-specific video data efficiently using asynchronous background processing.

The system is designed to be scalable, API-driven, and optimized for handling large datasets.

---

## Key Design Decisions

- Asynchronous processing used for scalability
- Duplicate records handled via update + logging strategy
- Custom fields stored as JSON for flexibility

---

## Tech Stack

* PHP 8.x
* Laravel 12+
* MySQL
* Docker & Docker Compose
* Laravel Queues

---

## Features

### Campaign Management

* Create campaigns linked to clients
* Store campaign start and end dates

### Campaign Data Processing

* Bulk upload of campaign data via API
* Supports dynamic `custom_fields` using JSON storage

### Asynchronous Processing

* Campaign data ingestion is handled via background jobs
* API responds immediately with HTTP 202 (Accepted)

### Duplicate Handling Strategy

If a `user_id` already exists within a campaign:

* The existing record is updated
* Custom fields are merged
* A log entry is created in `campaign_data_duplicate_logs`

Each duplicate attempt stores:

* Previous values
* Updated values
* Action taken

---

## Analytics Command

### Run the analytics report for a campaign

```bash
php artisan analytics:campaign {campaignId}
```

---

## Installation & Setup

### Clone the repository

```bash
git clone https://github.com/NancyRoseV/personalized-video-campaign-manager.git
cd personalized-video-campaign-manager
```

### Start the application using Docker

```bash
docker compose up -d --build
```

### Run database migrations

```bash
docker compose exec app bash
php artisan migrate
```

### Start the queue worker (required for background processing)

```bash
docker compose exec app bash
php artisan queue:work
```

---

## API Endpoints

### Create a Campaign

**POST /api/campaigns**

#### Request Body

```json
{
  "client_id": 1,
  "name": "Spring Launch",
  "start_date": "2026-04-16",
  "end_date": "2026-05-16"
}
```

#### Response

```json
{
  "message": "Campaign created successfully.",
  "data": { ... }
}
```

---

### Add Campaign Data

**POST /api/campaigns/{campaign_id}/data**

#### Request Body

```json
[
  {
    "user_id": "user_1",
    "video_url": "https://example.com/video-1.mp4",
    "custom_fields": {
      "first_name": "Nancy",
      "plan": "Premium"
    }
  }
]
```

#### Response

```json
{
  "message": "Campaign data accepted for processing."
}
```

---

## Campaign data is processed asynchronously using:

App\Jobs\ProcessCampaignData

This ensures fast API responses while heavy processing is handled in the background.
