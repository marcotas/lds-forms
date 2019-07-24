@servers(['web' => 'deployer@165.227.114.76'])

@setup
    $repository = 'git@gitlab.com:marcoT89/lds-forms.git';
    $domain = 'marcoavila.me';
    $releases_dir = "/var/www/$domain/releases";
    $app_dir = "/var/www/$domain";
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    run_composer
    run_yarn
    update_symlinks
    chown_on_release
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    composer install --prefer-dist --no-scripts -q -o
@endtask

@task('run_yarn')
    echo "Building frontend ({{ $release }})"
    cd {{ $new_release_dir }}
    yarn
    yarn prod
@endtask

@task('update_symlinks')
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

@task('chown_on_release')
    echo 'Testing sudo'
    echo {{ $sudo_password }} | sudo -S chown www-data:www-data -R {{ $app_dir }}
@endtask
