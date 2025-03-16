require("dotenv").config();

const DB_HOST = process.env.DB_HOST
const DB_USER = process.env.DB_USER
const DB_PASSWORD = process.env.DB_PASSWORD
const DB_DATABASE = process.env.DB_DATABASE
const DB_PORT = process.env.DB_PORT
const PORT = process.env.PORT

const express = require("express")
const app = express()
const mysql = require("mysql2")


const db = mysql.createPool({
   connectionLimit: 100,
   host: DB_HOST,
   user: DB_USER,
   password: DB_PASSWORD,
   database: DB_DATABASE,
   port: DB_PORT
})
db.getConnection((err, connection) => {
    if (err) {
        console.error("Error connecting to the database:", err);
        process.exit(1);
    } else {
        console.log("DB connected successfully: " + connection.threadId);

        app.listen(PORT, () => {
            console.log(`Server started on port ${DB_PORT}...`);
        });
    }
});
