# Back-End

The back-end is based on laravel.

## Environment

- php 5.6
- Laravel 5.2

## Installation

```
# Set up laravel
composer install

# MySQL
php artisan migrate

# Config
cp .env.example .env
vim .env
```

## API

### POST /image/add

Input:

```
[
    {
        "faceId": "b9a33aewq00-0b28-4c89-a48a-93b2a931774e",
        "faceRectangle": {
            "top": 120,
            "left": 814,
            "width": 87,
            "height": 87
        },
        "faceAttributes": {
            "gender": "male",
            "age": 24.4
        }
    },
	{
        "faceId": "b9a33asd00-0b28-4c89-a48a-93b2a931774e",
        "faceRectangle": {
            "top": 120,
            "left": 814,
            "width": 87,
            "height": 87
        },
        "faceAttributes": {
            "gender": "male",
            "age": 24.4
        }
    }
]
``` 

### GET /image/get

Output:

```
[
    {
        "faceId": "b9a33aewq00-0b28-4c89-a48a-93b2a931774e",
        "faceRectangle": {
            "top": 120,
            "left": 814,
            "width": 87,
            "height": 87
        },
        "faceAttributes": {
            "gender": "male",
            "age": 24.4
        }
    },
	{
        "faceId": "b9a33asd00-0b28-4c89-a48a-93b2a931774e",
        "faceRectangle": {
            "top": 120,
            "left": 814,
            "width": 87,
            "height": 87
        },
        "faceAttributes": {
            "gender": "male",
            "age": 24.4
        }
    }
]
``` 