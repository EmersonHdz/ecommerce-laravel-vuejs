# API Documentation: User Management

This API allows you to manage users in the system. It provides endpoints for listing, creating, updating, and deleting users.

---

## Base URL
All endpoints are relative to the base URL of your application:  http://localhost:8000/api/users


## Endpoints

### 1. List Users
Retrieve a paginated list of users.

- **URL**: `/api/admin/users`
- **Method**: `GET`
- **Query Parameters**:
  - `per_page` (optional): Number of users per page. Default is `10`.
  - `search` (optional): Search term to filter users.
  - `sort_field` (optional): Field to sort by. Default is `updated_at`.
  - `sort_direction` (optional): Sort direction (`asc` or `desc`). Default is `desc`.

- **Response**:
  - Status Code: `200 OK`
  - Body: Paginated list of users in JSON format.
    ```json
    {
      "data": [
        {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "is_admin": true,
          "created_at": "2023-10-01T12:00:00Z",
          "updated_at": "2023-10-01T12:00:00Z"
        },
        ...
      ],
      "links": {
        "first": "https://yourapp.com/api/admin/users?page=1",
        "last": "https://yourapp.com/api/admin/users?page=2",
        "prev": null,
        "next": "https://yourapp.com/api/admin/users?page=2"
      },
      "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 15
      }
    }
    ```

---

### 2. Create a User
Create a new user.

- **URL**: `/api/admin/users`
- **Method**: `POST`
- **Request Body**:
  - `name` (required): User's full name.
  - `email` (required): User's email address.
  - `password` (required): User's password.
  - Other fields as defined in the `CreateUserRequest` validation rules.

- **Response**:
  - Status Code: `201 Created`
  - Body: Details of the created user.
    ```json
    {
      "id": 2,
      "name": "Jane Doe",
      "email": "jane@example.com",
      "is_admin": true,
      "created_at": "2023-10-01T12:00:00Z",
      "updated_at": "2023-10-01T12:00:00Z"
    }

### 3. Update a User
Update an existing user.

- **URL**: `/api/admin/users/{id}`
- **Method**: `PUT` or `PATCH`
- **Request Body**:
  - `name` (optional): Updated full name.
  - `email` (optional): Updated email address.
  - `password` (optional): Updated password.
  - Other fields as defined in the `UpdateUserRequest` validation rules.

- **Response**:
  - Status Code: `200 OK`
  - Body: Details of the updated user.
    ```json
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane.smith@example.com",
      "is_admin": true,
      "created_at": "2023-10-01T12:00:00Z",
      "updated_at": "2023-10-02T12:00:00Z"
    }
    ```

---

### 4. Delete a User
Delete an existing user.

- **URL**: `/api/admin/users/{id}`
- **Method**: `DELETE`
- **Response**:
  - Status Code: `204 No Content`
  - Body: Empty.

---

## Models and Resources

### User Model
The `User` model represents a user in the system. It includes fields such as:
- `id`: Unique identifier.
- `name`: Full name of the user.
- `email`: Email address.
- `password`: Hashed password.
- `is_admin`: Boolean indicating if the user is an admin.
- `email_verified_at`: Timestamp when the email was verified.
- `created_by`: ID of the user who created this record.
- `updated_by`: ID of the user who last updated this record.
- `created_at`: Timestamp when the record was created.
- `updated_at`: Timestamp when the record was last updated.

### UserResource
The `UserResource` is used to format the user data returned by the API. It includes:
- `id`
- `name`
- `email`
- `is_admin`
- `created_at`
- `updated_at`

---

## Validation Rules

### CreateUserRequest
- `name`: Required, string, max 255 characters.
- `email`: Required, string, email, unique in the `users` table.
- `password`: Required, string, min 8 characters.

### UpdateUserRequest
- `name`: Optional, string, max 255 characters.
- `email`: Optional, string, email, unique in the `users` table (ignoring the current user).
- `password`: Optional, string, min 8 characters.

---

## Authentication
All endpoints require authentication. Ensure the request includes a valid authentication token in the headers.
