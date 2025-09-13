<h1 align="center">☁️ Cloud-Drive</h1>
<p align="center">
  A self-hosted cloud storage web app that works like Google Drive.  
  Upload, preview, organize, share, and manage your personal files — right from your browser.
</p>

---

## 🚀 Live Features

- 👤 **User Authentication**
- 📤 **Secure File Upload**
- 📁 **Folder Management**
- 🎥 **File Preview (PDF, Images, Videos)**
- 🔗 **Sharable Public File Links**
- 🗑️ **Recycle Bin with Restore & Permanent Delete**
- 📊 **Storage Usage Bar (Quota System) [Still in Progress]**
- 🔒 **Password Protected Sessions [Still in Progress]**

---

## 🛠️ Built With

| Tech | Role |
|------|------|
| `PHP` | Backend logic  
| `MySQL` | Database storage  
| `HTML + CSS` | Page structure & design  
| `JavaScript` | Client-side interactivity  
| `Bootstrap` | UI components & layout  
| `XAMPP` | Local testing environment  

---

## 📂 Folder Structure

```
Cloud-Drive/
├── index.php                → Login & Register
├── dashboard.php            → File manager interface
├── upload.php               → File upload handling
├── create_folder.php        → Folder creation logic
├── recycle_bin.php          → Soft deleted file list
├── restore_file.php         → Restore from bin
├── permanent_delete.php     → Permanent delete action
├── share.php                → Public access view
├── db/
│   └── db_config.php        → Database connection settings
├── files/                   → Uploaded file storage
├── assets/
│   └── css/style.css        → Custom styling
└── README.md
```

---

## 💻 How to Run Locally

> Requires XAMPP or any PHP-MySQL stack

1. Clone this repo:
   ```bash
   git clone https://github.com/Codesickm/Cloude-Drive.git
   ```

2. Move to `htdocs`:
   ```
   C:\xampp\htdocs\Cloud-Drive
   ```

3. Start **Apache** & **MySQL** via XAMPP

4. Create a MySQL database:
   ```
   Database name: clouddrive
   ```

5. Import your SQL tables manually or use phpMyAdmin

6. Visit:
   ```
   http://localhost/Cloud-Drive
   ```

---

## 🌍 How to Host Online

You can host **Cloud-Drive** via:

- 🔌 Your own PC (port forwarding with public IP)
- ☁️ Any PHP-MySQL compatible host (e.g. Hostinger, InfinityFree)
- 🔄 Dynamic DNS via **No-IP**

✅ Hosting on your computer? Set up `port 80` in router  
✅ Need help? Ask Nayak 😉

---

## 🙋‍♂️ Author

**Mohit (Codesickm)**  
🚀 GitHub: [https://github.com/Codesickm](https://github.com/Codesickm)  
💛 Developer Mates: Vaishali Mohit Ishita

---

## 📄 License

This project is under [MIT License](LICENSE)  
Feel free to fork, modify, and use for personal or educational purposes.

---

> ⭐ Star this repo if you like it  
> 🍴 Fork it if you want to build on it  
> 🤝 Let’s connect and build awesome things
