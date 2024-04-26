<a name="readme-top"></a>

[![LinkedIn][linkedin-shield]][linkedin-url]

<br />
<div align="center">
<h3 align="center">Movie Review Blog</h3>

  <p align="center">
    This application allows: <br />
        guests: <br />
        -register/log in <br />
        -send an email using the form in the CONTACT tab <br />
        user:<br />
        -the same as a guest <br />
        -comment/add a rating to a movie <br />
        -editing account information <br />
        admin :<br />
        -the same as guest and user<br />
        -admin panel (User Management, Content Management, Analytics, Backups) <br />
    <br />
    <a href="https://github.com/plabanowksi/movieReviewBlog"><strong>Explore the docs »</strong></a>
    <br />
  </p>
</div>


<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>


<!-- ABOUT THE PROJECT -->
## About The Project
<p align="left">Main page looks like this. After login in you can comment and rate movies.</p>

[![Product Name Screen Shot][product-screenshot]](https://example.com)

<p align="right">(<a href="#readme-top">back to top</a>)</p>



### Built With
* [![Alpine][Alpine.js]][Alpine-url]
* [![Symfony][Symfony.com]][Symfony-url]
* [![Bootstrap][Bootstrap.com]][Bootstrap-url]
* [![JQuery][JQuery.com]][JQuery-url]

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running follow these simple example steps.

### Installation

1. Clone the repo:
   ```sh
   git clone https://github.com/plabanowksi/movieReviewBlog.git
   ```
2. Download and install composer:
    ```
    https://getcomposer.org/
    ```
    Command for installation
    ```
    composer install
    ```
3. Download XAMPP 8.2.12 and create new database:
    ```
    https://www.apachefriends.org/pl/download.html    
    ```
    After instalation go to php.ini ( localdisc > xampp > php) u need to uncomment this extensions:
    ```
        extension=curl
        extension=fileinfo
        extension=mysqli
        extension=pdo_mysql
        extension=zip
    ```
4.  Create new database:
    You can do it by localhost/phpmyadmin in XAMPP <br>
    I prefer using DBeaver for it, all u have to do is turn on your XAMPP and create new connection + create new database <br>
    After creating new database, set name 'db_movies' or what ever you like, but then change database name in '.env' (DATABASE_URL and in DATABASE_NAME)<br>
    Now we can execute command that will fill our database with basic data
    ```
    symfony console doctrine:fixtures:load --no-interaction
    ```
5. Install NPM packages:
    ```
    npm install
    ```
6. Open new terminal in project dir and use one of following commands:
    ```
    npm run dev / npm run
    ```
7. Open new terminal in project dir (u can use GIT) and start project:
    ```
    symfony server:start
    ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTACT -->
## Contact

Your Name - https://www.linkedin.com/in/pawe%C5%82-%C5%82abanowski-854417274/

Project Link: [https://github.com/plabanowksi/movieReviewBlog](https://github.com/plabanowksi/movieReviewBlog)

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- MARKDOWN LINKS & IMAGES -->
[license-shield]: https://img.shields.io/github/license/plabanowksi/movieReviewBlog.svg?style=for-the-badge
[license-url]: https://github.com/plabanowksi/movieReviewBlog/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/pawe%C5%82-%C5%82abanowski-854417274/
[product-screenshot]: public/images/mainpage.png

[Alpine.js]: https://img.shields.io/badge/alpinejs-white.svg?style=for-the-badge&logo=alpinedotjs&logoColor=%238BC0D0
[Alpine-url]: https://alpinejs.dev/
[Symfony.com]: https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white
[Symfony-url]: https://symfony.com/
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com 
