# Appointment App

## Setup & Running

```bash
# Clone the repository
git clone https://github.com/georgirtodorov/gt-labforty.git
cd gt-labforty

# Copy environment file
cp .env.example .env

# Make helper scripts executable
chmod +x scripts/*.sh

# Run following scripts
./scripts/build.sh #build images and up containers

# !!! wait couple of second till db and laravel boot completely !!!
./scripts/migrate.sh #create db tables and seed time slots for the next month
```
# Access Points

Application: http://localhost:8000

phpMyAdmin: http://localhost:8080

# Optional: Generate additional slots

```bash
# Generate slots with default values (From today for 1 month ahead, 05:00 - 14:00 UTC, 30 min intervals)
./slots.sh # default for the next 1 month wth 30 mins interval

# Generate slots for a specific date range
./slots.sh --start-date=2026-05-01 --end-date=2026-05-10

# Generate slots with custom working hours and 45-minute intervals
./slots.sh --start-date=2026-06-01 --end-date=2026-06-15 --start=09:00 --end=18:00 --interval=45
````

# Start application

```bash
./scripts/up.sh
```

# Stop application

```bash
./scripts/down.sh
```

# Remove project's images and volumes

```bash
./scripts/terminate.sh
```

## API (/api/v1/)

### 1. List all appointments

* **URL:** `/api/v1/appointments`
* **Method:** `GET`
* **Query params:**
    * `identifier` (Optional)
    * `date_from` (Optional UTC ISO 8601 ex. `2026-05-16T00:00:00Z`)
    * `date_to` (Optional UTC ISO 8601 ex.`2026-05-16T23:59:59Z`)
    * `page` (Optional)

**Response (200 OK):**

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 48,
            "status": "requested",
            "start_at": "2026-05-18T07:00:00Z",
            "end_at": "2026-05-18T07:30:00Z",
            "first_name": "Keyshawn",
            "last_name": "Price",
            "identifier": "4618501157",
            "notification_type": "sms"
        }
    ],
    "next_page_url": "http://localhost:8000/api/v1/appointments?page=2",
    "prev_page_url": null,
    "per_page": 1,
    "total": 50
}
```

### 2. Create appointment

* **URL:** `/api/v1/appointments`
* **Method:** `POST`
* **Request Body (JSON):**

```json
{
    "start_at": "2026-05-18T07:00:00Z",
    "end_at": "2026-05-18T07:30:00Z",
    "first_name": "Keyshawn",
    "last_name": "Price",
    "identifier": "4618501157",
    "notification_type": "sms"
}
```

**Response (200 OK):**

```json
{
    "appointment": {
        "status": "requested",
        "client_id": 31,
        "notification_type": "sms",
        "description": null,
        "updated_at": "2026-05-16T11:25:02.000000Z",
        "created_at": "2026-05-16T11:25:02.000000Z",
        "id": 51,
        "time_slot": {
            "id": 8,
            "start_at": "2026-05-18T07:00:00Z",
            "end_at": "2026-05-18T07:30:00Z",
            "appointment_id": 51,
            "created_at": "2026-05-16T11:03:44.000Z",
            "updated_at": "2026-05-16T11:25:02.000Z",
            "business_timezone": "Europe\/Sofia"
        },
        "client": {
            "id": 31,
            "user_id": null,
            "first_name": "Deyshawn",
            "last_name": "Brice",
            "identifier": "8765456753",
            "created_at": "2026-05-16T11:25:02.000000Z",
            "updated_at": "2026-05-16T11:25:02.000000Z",
            "deleted_at": null
        }
    }
}
```

### 3. Preview appointment

* **URL:** `/api/v1/appointments/{id}`
* **Method:** `GET`

**Response (200 OK):**

```json
{
    "id": 51,
    "client_id": 31,
    "status": "requested",
    "notification_type": "sms",
    "description": null,
    "created_at": "2026-05-16T11:25:02.000000Z",
    "updated_at": "2026-05-16T11:25:02.000000Z",
    "deleted_at": null,
    "time_slot": {
        "id": 8,
        "start_at": "2026-05-18T08:30:00Z",
        "end_at": "2026-05-18T09:00:00Z",
        "appointment_id": 51,
        "created_at": "2026-05-16T11:03:44.000Z",
        "updated_at": "2026-05-16T11:25:02.000Z",
        "business_timezone": "Europe/Sofia"
    },
    "client": {
        "id": 31,
        "user_id": null,
        "first_name": "Keyshawn",
        "last_name": "Price",
        "identifier": "8765456753",
        "created_at": "2026-05-16T11:25:02.000000Z",
        "updated_at": "2026-05-16T11:25:02.000000Z",
        "deleted_at": null
    }
}
```

### 4. Future appointment

* **URL:** `/api/v1/appointments/future/{id}`
* **Method:** `GET`

**Response (200 OK):**

```json
[
    {
        "id": 14,
        "client_id": 10,
        "status": "confirmed",
        "notification_type": "email",
        "description": "Voluptatem aperiam excepturi eum ea aut aperiam autem quis iure non quod aut laborum.",
        "created_at": "2026-05-16T11:03:47.000000Z",
        "updated_at": "2026-05-16T11:03:47.000000Z",
        "deleted_at": null,
        "time_slot": {
            "id": 1,
            "start_at": "2026-05-18T05:00:00Z",
            "end_at": "2026-05-18T05:30:00Z",
            "appointment_id": 14,
            "created_at": "2026-05-16T11:03:44.000Z",
            "updated_at": "2026-05-16T11:12:49.000Z",
            "business_timezone": "Europe\/Sofia"
        },
        "client": {
            "id": 10,
            "user_id": null,
            "first_name": "Fausto",
            "last_name": "Graham",
            "identifier": "8100165347",
            "created_at": "2026-05-16T11:03:46.000000Z",
            "updated_at": "2026-05-16T11:03:46.000000Z",
            "deleted_at": null
        }
    },
    {
        "id": 40,
        "client_id": 10,
        "status": "confirmed",
        "notification_type": "sms",
        "description": "Rerum voluptas in voluptatem cumque quo molestiae quos impedit excepturi consequuntur possimus.",
        "created_at": "2026-05-16T11:03:47.000000Z",
        "updated_at": "2026-05-16T11:03:47.000000Z",
        "deleted_at": null,
        "time_slot": {
            "id": 45,
            "start_at": "2026-05-20T09:00:00Z",
            "end_at": "2026-05-20T09:30:00Z",
            "appointment_id": 40,
            "created_at": "2026-05-16T11:03:44.000Z",
            "updated_at": "2026-05-16T11:03:47.000Z",
            "business_timezone": "Europe\/Sofia"
        },
        "client": {
            "id": 10,
            "user_id": null,
            "first_name": "Fausto",
            "last_name": "Graham",
            "identifier": "8100165347",
            "created_at": "2026-05-16T11:03:46.000000Z",
            "updated_at": "2026-05-16T11:03:46.000000Z",
            "deleted_at": null
        }
    }
]
```

### 5. Delete appointment

* **URL:** `/api/v1/appointments/{id}`
* **Method:** `DELETE`

### 6. Update appointment

* **URL:** `/api/v1/appointments/{id}`
* **Method:** `PUT`

* **Request Body (JSON):**

```json
{
    "start_at": "2026-05-20T10:00:00Z",
    "end_at": "2026-05-20T10:30:00Z",
    "first_name": "New first name",
    "last_name": "New last name",
    "identifier": "1234567890",
    "notification_type": "email",
    "status": "requested"
}
```

**Response (200 OK):**

```json
{
    "appointment": {
        "id": 14,
        "client_id": 34,
        "status": "confirmed",
        "notification_type": "email",
        "description": "New description",
        "created_at": "2026-05-16T11:03:47.000000Z",
        "updated_at": "2026-05-16T15:50:18.000000Z",
        "deleted_at": null,
        "time_slot": {
            "id": 47,
            "start_at": "2026-05-20T10:00:00Z",
            "end_at": "2026-05-20T10:30:00Z",
            "appointment_id": 14,
            "created_at": "2026-05-16T11:03:44.000Z",
            "updated_at": "2026-05-16T15:50:18.000Z",
            "business_timezone": "Europe\/Sofia"
        },
        "client": {
            "id": 34,
            "user_id": null,
            "first_name": "New first name",
            "last_name": "New last name",
            "identifier": "1234567890",
            "created_at": "2026-05-16T15:50:18.000000Z",
            "updated_at": "2026-05-16T15:50:18.000000Z",
            "deleted_at": null
        }
    }
}
```

### 7. Available slots for date

* **URL:** `/api/v1/available-slots?date=2026-05-20`
* **Method:** `GET`
* **Query params:**
    * `date` ( Date format:Y-m-d ex. `2026-05-20` )

**Response (200 OK):**

```json
[
    {
        "id": 37,
        "start_at": "2026-05-20T05:00:00Z",
        "end_at": "2026-05-20T05:30:00Z",
        "business_timezone": "Europe/Sofia"
    }
]
```

# Tests
```bash
./scripts/unit.sh
```

