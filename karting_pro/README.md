### 👤 Creating an Admin User
To grant admin privileges to an existing user:
```sql
UPDATE users SET role = 'admin' WHERE id = 1;
```
Replace `1` with the correct user ID.