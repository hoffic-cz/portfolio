# Hoffic.dev Portfolio Website
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=hoffic.cz_portfolio&metric=coverage)](https://sonarcloud.io/dashboard?id=hoffic.cz_portfolio)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=hoffic.cz_portfolio&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=hoffic.cz_portfolio)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=hoffic.cz_portfolio&metric=alert_status)](https://sonarcloud.io/dashboard?id=hoffic.cz_portfolio)

This project is a website for showing information about myself and linking to relevant resources. It
is meant to impress, let people interact with it and be impressed even more :)

[VIDEO PLACEHOLDER]



## Live version

You can find a live version of this project at [https://hoffic.dev/](https://hoffic.dev/) unless, of
course, it is down. In that case be sure I'm working on fixing it.



## AlgoExpert Project Contest

This project is also a submission for the [AlgoExpert](https://www.algoexpert.io/) software engineering
project contest.




## Support

If you are familiar with submitting issues, please do so [HERE](https://gitlab.com/hoffic.cz/portfolio/issues).

If you don't feel like filing an issue or you'd like a more casual conversation, send me an email at
[petr@hoffic.dev](mailto:petr@hoffic.cz).

I will be extremely happy for any suggestions or issues you find ❤️



## Setting up for development

### Requirements

* Docker (19.03.5+)
* Docker Compose (1.25.1+)
* PHPStorm (optional)

### Running website locally

Spin up the docker compose array. This will automatically build and start all services. You can
optionally supply the `-d` flag to run it as a daemon in the background.

```bash
docker-compose up
```

In a new terminal window unless you supplied the -d flag

```bash
docker-compose exec app ash -c "composer install"
docker-compose exec app ash -c "yarn build"
```

Navigate to [http://localhost:8080](http://localhost:8080) in your browser.

### Permission issues

Should you encounter permission issues, which manifest themselves by lots of red in the console, make
sure you are a member of the `nogroup` group and that everything in the project directory is owned
by `nobody:nogroup`.

```bash
sudo chown -R nobody:nogroup ./
```

### Running tests

Run the following command to execute the PHPUnit test suite.

```bash
docker-compose exec app ash -c "php bin/phpunit"
```

### Quality gates

Code quality is measured using [SonarQube's Quality Gates](https://sonarcloud.io/dashboard?id=hoffic.cz_portfolio).
All metrics shall retain the `A` rating.



## License

Copyright © 2020 Petr Hoffmann. All rights reserved.
Please contact me at [petr@hoffic.dev](mailto:petr@hoffic.dev) for anything beyond fair use.

I have not decided how I want to treat this project license-wise in the future.
Message me and I promise we'll figure something out :)
