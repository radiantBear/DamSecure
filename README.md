# DamSecure IoT Portal

[![DeepSource](https://app.deepsource.com/gh/radiantBear/DamSecure.svg/?label=active+issues&show_trend=true&token=6erHwABJ8RhceOpSeqMf9rU3)](https://app.deepsource.com/gh/radiantBear/DamSecure/)
[![DeepSource](https://app.deepsource.com/gh/radiantBear/DamSecure.svg/?label=code+coverage&show_trend=true&token=6erHwABJ8RhceOpSeqMf9rU3)](https://app.deepsource.com/gh/radiantBear/DamSecure/)

## Development Setup
1. Clone the repo & open it in your IDE
1. Copy `.env.example` to `.env` and set a secure value for `DB_PASSWORD`
1. Generate an SSH commit-signing key at `~/.ssh/git_signing` (and 
    `~/.ssh/git_signing.pub`)
1. Tell GitHub about that key by following [these steps][gh_signing]
1. Tell Git to sign commits using that key (omit `--global` if you only want to do so for
    this repo)
    ```console
    foo@bar:~/DamSecure$ git config --global gpg.format ssh
    foo@bar:~/DamSecure$ git config --global user.signingkey ~/.ssh/gitsigning.pub
    foo@bar:~/DamSecure$ git config --global commit.gpgsign true
    ```
1. Perform the following step for your dev environment of choice:
    - If using VS Code and local dev containers, install the
        [Dev Containers extension][devcontainers] and reopen the project in the configured
        dev container
    - If editing locally and running the dev server via Docker Compose, run
        `docker compose up --build --watch`
        - Other commands (e.g. ones that require `php`) will need to be prefixed with
            `docker compose exec -it apache_php`
            - Created/modified files will need to be copied back to your local repository
                (in order to be committed to version control) via
                `docker compose cp apache_php:/app/path/to/file ./path/to/file` where
                `path/to/file` is replaced with the filepath to copy
1. Run `php artisan key:generate`
1. Start developing!

> [!IMPORTANT]
>
> OSU's servers run PHP as a dedicated user that is not part of the user or group for this
> project's files. To ensure that PHP can access all files to execute code and render
> pages, all accessed files need the permissions `0o664` and all directories need the
> permissions `0o775`. For simplicity, you can add the
> [`scripts/allow.sh`](./scripts/allow.sh) script as a pre-commit hook to ensure all
> committed files have the correct permissions:
>
> ```console
> foo@bar:~/DamSecure$ cp ./scripts/allow.sh ./.git/hooks/pre-commit
> ```


[devcontainers]: https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers
[gh_signing]: https://docs.github.com/en/authentication/managing-commit-signature-verification/about-commit-signature-verification#ssh-commit-signature-verification

## Deploying
To deploy a DamSecure update to OSU's servers, follow these steps:
1. Ensure all updates are merged to the `main` branch of this repository
1. SSH into OSU's server and `cd` to the directory where DamSecure's source lives
1. Run `git pull` to get the latest changes
1. Run `bash scripts/allow.sh` to ensure all files have the necessary permissions for PHP
    to load them
1. Run `php artisan migrate` to apply any database changes

## Laravel
This project is built on Laravel to simplify development. Before making changes, check out
their [directory structure][laravel-directory] and [architecture concepts][laravel-arch]
documentation.

### Routes
Laravel [routes][laravel-routes] are used to determine what code to execute when a given
HTTP request is made.
- All routes for the web app should be placed in [`/routes/web.php`](./routes/web.php).
    These routes' handlers can access the currently-logged in user (based on the request's
    cookies) via the `auth()->user()` method.
- All routes for the user-facing API should be placed in
    [`/routes/api.php`](./routes/api.php). These routes' handlers can access the project
    that the request is for via the `auth()->user()` method. This is still called *user*
    because Laravel's [personal access tokens][laravel-tokens] feature has been adapted to
    authenticate projects instead of users. This design decision was made because
    DamSecure is intended for group projects, where it makes more sense for the team to
    have 1 API token for the project, rather than require each member to generate their
    own (what does it mean for the group's IoT thermometer to authenticate as John Doe,
    anyway?).

### Testing
Every change should be thoroughly tested. Integration tests are preferred since most of
this app's behavior involves visiting pages and making API calls, with little complex
backend logic. This makes integration tests more efficient than unit tests for ensuring
proper functionality. Integration tests live in [`/tests/Feature`](./tests/Feature/) and
can be created with `php artisan make:test <TestName>` (where `<TestName>` is e.g.
`UserTest`).

Complex logic should be placed in a class in [`/app/Services`](./app/Services/) and
*should* be unit tested. Unit tests live in [`/tests/Unit](./tests/Unit/) and can be
created with `php artisan make:test <TestName> --unit` (where `<TestName>` is e.g.
`DataServiceTest.php`).

For more details on testing, check out Laravel's [testing documentation][laravel-tests].
All tests can be run with `php artisan test`. Tests should **always** be run before
creating a PR and new logic should **always** be tested.

### Database Changes
The database for DamSecure is "version-controlled" via
[Laravel database migrations][laravel-migrations]. To make changes to the database schema,
follow these steps to create and apply a migration:
1. Run `php artisan make:migration <migration_name>` where `<migration_name>` is replaced
    with a short name for the changes being made (e.g. `create_download_table`)
1. Open the new file in [`/database/migrations`](./database/migrations/)
1. Replace the `up()` method's implementation with one that makes all needed changes to
    the database
1. Replace the `down()` method's implementation with one that undoes all changes to the
    database
    - Ideally, `up()` and `down()` should be implemented such that running `up(); down();`
        is non-destructive for data currently in the database
1. Run `php artisan migrate` to apply the changes to your dev database
1. Update the [model classes](./app/Models/) to match the new schema and fix any
    QueryBuilder calls this breaks
1. Update the [test factory classes](./database/factories/) and
    [test seed script](./database/seeders/DatabaseSeeder.php) to match the new schema
1. Commit the modified files

To apply these changes to the production database, run `php artisan migrate --force` from
the production PHP server after pulling the latest version of the code to that server.

[laravel-arch]: https://laravel.com/docs/10.x/lifecycle
[laravel-directory]: https://laravel.com/docs/10.x/structure
[laravel-migrations]: https://laravel.com/docs/10.x/migrations
[laravel-routes]: https://laravel.com/docs/10.x/routing
[laravel-tests]: https://laravel.com/docs/10.x/testing
[laravel-tokens]: https://laravel.com/docs/10.x/sanctum#api-token-authentication
