#Code Challange

This is a simple application for add post to Medium.

#How to install the package.

- clone from .env.example as .env
- Then run `php artisan migrate` & `php artisan jwt:secret`

# Generate Medium token

- [Medium](https://medium.com/me/settings) - goto to this page & get your token

# Register

- `/api/register` - to register
- please fill this form-data to register name,email,password,password_confirmation,medium_key(add your medium token here)

# Login

- `/api/login` login using email & password
- then get your auth token

# Post story

- Authorize with Bearer using your auth token
- then fill this form-data to create & send post to medium
- title,content,image,publish_status
- publish_status keep as draft
