<a name="readme-top"></a>
[![MIT License][license-shield]][license-url]
<div align="center">
<h3 align="center">SSO with Symfony 5.4</h3>
  <p align="center">
    Symfony skeleton application ready for SSO integration
    <br />
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
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->
## About The Project

This project is meant to be a starting point for adding SSO capabilities to a Symfony 5.4 application with JWT 
authentication.
<p align="right"><a href="#readme-top">:arrow_up:</a></p>

### Built With

[![Symfony][Symfony-shield]][Symfony-url]  
[![Docker][Docker-shield]][Docker-url]  

<p align="right"><a href="#readme-top">:arrow_up:</a></p>

<!-- GETTING STARTED -->
## Getting Started

The application is build with Symfony v5.4 and running on PHP 7.4.  
It has installed the 
[Lexik JWT authentication bundle](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html)  for 
JWT authentication. For simplicity the users ar loaded from `./config/users.php` with the help of `UserProvider.
php`. The user entity contains only the `username` and `email`.  
The application exposes `/api/v1/users` API which returns the decoded JWT. 

### Prerequisites

To use this software you must have installed [git](https://git-scm.com/downloads) and 
[docker](https://docs.docker.com/get-docker/).

### Installation

1. Clone the repo
   ```shell
   git clone git@github.com:mariuspop86/test-app.git
   ```
2. Create and run the containers 
   ```shell
   docker-composer up -d
   ```
3. Install dependencies
   ```shell
   docker-compose exec php composer install
   ```
<p align="right"><a href="#readme-top">:arrow_up:</a></p>

<!-- USAGE EXAMPLES -->
## Usage

Once you installed you can access it at [localhost:8000](http://localhost:8000). 
> If you already have port 8000 in use, update `./docker-compose.yml` file to a free port.

To login make a request at
```shell
curl --location 'http://localhost:8000/api/login_check' \
--header 'Content-Type: application/json' \
--data '{"username":"test","password":"test"}'
```
This will generate a token witch can be used to authorize the user to access the private API by adding the token in 
the `bearer` query params 
```shell
curl --location --request GET 'http://localhost:8000/api/v1/users?bearer={token}' \
--header 'Content-Type: application/json'
```
<p align="right"><a href="#readme-top">:arrow_up:</a></p>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[license-shield]: https://img.shields.io/github/license/mariuspop86/test-app.svg?style=for-the-badge
[license-url]: https://github.com/mariuspop86/test-app/blob/main/LICENSE.txt
[Symfony-shield]: https://symfony.com/favicons/favicon-32x32.png
[Symfony-url]: https://symfony.com/
[Docker-shield]: https://www.docker.com/favicon.ico
[Docker-url]: https://www.docker.com/
