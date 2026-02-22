# DamSecure IoT Portal

## Development Setup
1. Clone the repo & open it in your IDE
1. Copy `.env.example` to `.env` and set a secure value for `DB_PASSWORD`
1. Generate an SSH commit-signing key at `~/.ssh/git_signing` (and 
    `~/.ssh/git_signing.pub`)
1. Tell GitHub about that key by following [these steps][gh_signing]
1. Tell Git to sign commits using that key (omit `--global` if you only want to do so for
    this repo)
    ```console
    foo@bar:~$ git config --global gpg.format ssh
    foo@bar:~$ git config --global user.signingkey ~/.ssh/gitsigning.pub
    foo@bar:~$ git config --global commit.gpgsign true
    ```
1. Perform the following step for your dev environment of choice:
    - If using VS Code and local dev containers, install the
        [Dev Containers extension][devcontainers] and reopen the project in the configured
        dev container
    - If editing locally and running the dev server via Docker Compose, run
        `docker compose up --build --watch`
        - Other commands (e.g. ones that require `php`) will need to be prefixed with
            `docker compose exec -it apache_php`
1. Run `php artisan key:generate`
1. Start developing!


[devcontainers]: https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers
[gh_signing]: https://docs.github.com/en/authentication/managing-commit-signature-verification/about-commit-signature-verification#ssh-commit-signature-verification
