# KeepUs

A lightweight, mobile-friendly PHP web application designed for couples to connect, play games, and share memories in a private, romantic, and secure environment. Optimized for 3G networks, KeepUs focuses on simplicity, speed, and a delightful user experience.

## Features

*   **Interactive Games:** Engage with classic couple games like "20 Questions," "Would You Rather," "This or That," and "Two Truths and a Lie."
*   **Shared Notes:** Leave private, heartfelt notes for your partner.
*   **Media Sharing:** Easily upload and share pictures and voice notes to capture and relive special moments.
*   **Content Recommendations:** Share links to online content, articles, or videos that you think your partner would enjoy.
*   **Admin Panel (Planned/Future):** Functionality to easily add new games and manage content (as per initial requirements).

## Technology Stack

*   **Frontend:** HTML5, CSS3 (mobile-first design), JavaScript.
*   **Backend:** PHP.
*   **Database:** MySQL.

## Getting Started

### Prerequisites

*   A web server (e.g., Apache, Nginx) with PHP support (PHP 7.4+ recommended).
*   MySQL database server.
*   Composer (for PHP dependency management, if any are introduced).

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/mwizerwahf/keepus.git
    cd KeepUs
   

2.  **Database Setup:**
    *   Create a MySQL database (e.g., `keepus_db`).
    *   Import the initial database schema (a `schema.sql` file will be provided or generated).
    *   Update `api/config.php` with your database credentials.

3.  **Web Server Configuration:**
    *   Point your web server's document root to the `KeepUs` directory.
    *   Ensure PHP is correctly configured and enabled.

4.  **Access the Application:**
    *   Open your web browser and navigate to the URL where you've deployed KeepUs (e.g., `http://localhost/KeepUs`).

## Usage

*   **Register/Login:** Create an account for yourself and your partner.
*   **Explore Games:** Navigate to the games section to start playing.
*   **Share & Connect:** Use the notes, pictures, voice notes, and links features to interact.

## Contributing

Contributions are welcome! Please feel free to fork the repository, make your changes, and submit a pull request.

## License

This project is open-source and available under the [MIT License](LICENSE.md). *(Note: A `LICENSE.md` file will need to be created.)*

---
