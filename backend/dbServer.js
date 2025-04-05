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
const bcrypt = require("bcrypt")
app.use(express.json())
const cors = require("cors");

//Was running into a cors error when loading the login.html page, this fix from Stack seems to have fixed it
const corsOptions = {
  origin: ["http://localhost:5500", "http://127.0.0.1:5500"], // Allow only your frontend
  methods: "GET,POST,PUT,DELETE,OPTIONS", // Allow necessary methods
  allowedHeaders: "Origin, X-Requested-With, Content-Type, Accept",
  credentials: true // Allow cookies & authentication headers
};

// Use CORS Middleware
app.use(cors());

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
        console.error("Error connecting to the DB:", err);
        process.exit(1);
    } else {
        console.log("Connected to DB: " + connection.threadId);

        app.listen(PORT, () => {
            console.log(`Server started on port ${DB_PORT}...`);
        });
    }
});

//Create a new user
app.post ("/createUser", async (req, res) => {
    const { username, password, email, name } = req.body;

    if (!username || !password) {
        return res.status(400).json({ error: "Invalid Registration" });
    }

    //This encrypts the password the user inputs
    const hashedPassword = await bcrypt.hash(password, 10);

    db.getConnection((err, connection) => {
        if (err) {
            return res.status(500).json({ error: "Failed to connect to the database" });
        }

        const searchQuery = "SELECT * FROM users WHERE username = ?";

        connection.query(searchQuery, [username], (err, result) => {
            if (err) {
                connection.release();
                return res.status(500).json({ error: "Error checking if user exists" });
            }

            if (result.length > 0) {
                connection.release();
                return res.status(409).json({ error: "User already exists" });
            }

            //This should help prevent SQL injections as it is seperating SQL commands from the user's input, using "?" will store the user's inputs as strings and will not be apart of the sql query command
            const insertQuery = "INSERT INTO users (username, password_hash, email, name) VALUES (?, ?, ?, ?)";
            connection.query(insertQuery, [username, hashedPassword, email, name], (err, result) => {
                connection.release();
                if (err) {
                    return res.status(500).json({ error: "Error creating user" });
                }

                res.status(201).json({
                    message: "User created successfully",
                    userId: result.insertId,
                });
            });
        });
    });
})

//Login
app.post("/login", async (req, res) => {
    const { username, password } = req.body;

    if (!username || !password) {
        return res.status(400).json({ error: "Invalid Username or Password" });
    }
    //Connect to the database
    db.getConnection((err, connection) => {
        if (err) {
            return res.status(500).json({ error: "Failed to connect to the database" });
        }

        const searchQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
        //Check to see if the user exists
        connection.query(searchQuery, [username, username], async (err, result) => {
            if (err) {
                connection.release();
                return res.status(500).json({ error: "Error checking if user exists" });
            }

            if (result.length === 0) {
                connection.release();
                return res.status(401).json({ error: "Invalid Username or Password" });
            }

            const user = result[0];
            const passwordMatch = await bcrypt.compare(password, user.password_hash);

            if (!passwordMatch) {
                connection.release();
                return res.status(401).json({ error: "Invalid Username or Password" });
            }

            res.status(200).json({
                message: "Login successful",
                userId: user.id,
            });
        });
    });
})
