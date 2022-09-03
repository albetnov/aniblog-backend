<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AniBlog Backend</title>
    @vite('resources/css/app.css')
</head>

<body>
    <header class="py-3 lg:py-5 px-2 bg-sky-300">
        <h1 class="text-2xl lg:text-4xl text-white text-center">AniBlog Backend Endpoints</h1>
    </header>
    <main class="flex justify-center mt-3 gap-6 flex-col lg:flex-row lg:items-start lg:flex-wrap">
        <x-card title="Authentication">
            <x-table>
                <tr>
                    <x-method type="get" />
                    <x-route>sanctum/csrf-cookie</x-route>
                    <x-blank />
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/login</x-route>
                    <x-json :data="['email', 'password']" />
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/register</x-route>
                    <x-json :data="['name', 'email', 'password', 'password_confirmation']" />
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/logout</x-route>
                    <x-blank />
                </tr>
            </x-table>
            <x-alert type="info">
                Since AniBlog Authentication Backend provided by default <a href="https://laravel.com/docs/9.x/fortify"
                    class="font-semibold underline hover:no-underline" target="_blank">Laravel
                    Fortify</a>. <br> There may be more routes you can discover. However, please note that <strong>2FA
                    are
                    disabled.</strong>
            </x-alert>
        </x-card>

        <x-card title="Categories">
            <x-alert type="info">
                All of this routes requires Sanctum Authentication & User Logged In by default.
            </x-alert>
            <x-table addon="Permission">
                <tr>
                    <x-method type="get" />
                    <x-route>/api/categories</x-route>
                    <x-blank />
                    <x-td>
                        Read Category
                    </x-td>
                </tr>
                <tr>
                    <x-method type="get" />
                    <x-route>/api/categories/{id}</x-route>
                    <x-blank />
                    <x-td>
                        Read Category
                    </x-td>
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/api/categories</x-route>
                    <x-json :data="['name', 'details']" />
                    <x-td>
                        Create Category
                    </x-td>
                </tr>
                <tr>
                    <x-method type="put" />
                    <x-route>/api/categories/{id}</x-route>
                    <x-json :data="['name', 'details']" />
                    <x-td>
                        Update Category
                    </x-td>
                </tr>
                <tr>
                    <x-method type="delete" />
                    <x-route>/api/categories/{id}</x-route>
                    <x-blank />
                    <x-td>Delete Category</x-td>
                </tr>
            </x-table>
        </x-card>

        <x-card title="Blogs">
            <x-alert type="info">
                All of this routes requires Sanctum Authentication & User Logged In by default.
            </x-alert>
            <x-table addon="Permission">
                <tr>
                    <x-method type="get" />
                    <x-route>/api/blogs</x-route>
                    <x-blank />
                    <x-td>
                        Read Blog
                    </x-td>
                </tr>
                <tr>
                    <x-method type="get" />
                    <x-route>/api/blogs/{id}</x-route>
                    <x-blank />
                    <x-td>
                        Read Blog
                    </x-td>
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/api/blogs</x-route>
                    <x-json :data="['title', 'content', 'categories[]']" />
                    <x-td>
                        Create Blog
                    </x-td>
                </tr>
                <tr>
                    <x-method type="put" />
                    <x-route>/api/blogs/{id}</x-route>
                    <x-json :data="['title', 'content', 'categories[]']" />
                    <x-td>
                        Update Blog
                    </x-td>
                </tr>
                <tr>
                    <x-method type="delete" />
                    <x-route>/api/blogs/{id}</x-route>
                    <x-blank />
                    <x-td>Delete Blog</x-td>
                </tr>
            </x-table>
        </x-card>
        <x-card title="Mobile">
            <x-table :addon="['Authentication', 'Purpose']">
                <tr>
                    <x-method type="post" />
                    <x-route>/api/mobile/token</x-route>
                    <x-json :data="['email', 'password', 'device_name']" />
                    <x-td>No</x-td>
                    <x-td>Issue a token</x-td>
                </tr>
                <tr>
                    <x-method type="delete" />
                    <x-route>/api/mobile/token/revoke</x-route>
                    <x-blank />
                    <x-td>Yes</x-td>
                    <x-td>Revoking a token</x-td>
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/api/mobile/token/new</x-route>
                    <x-json :data="['email', 'name', 'password', 'password_confirmation', 'device_name']" />
                    <x-td>No</x-td>
                    <x-td>Create a new user. But with token</x-td>
                </tr>
            </x-table>
        </x-card>
        <x-card title="Users" addon="2xl:w-1/2">
            <x-alert type="info">
                All of this routes requires Sanctum Authentication & User Logged In by default.
            </x-alert>
            <x-table :addon="['Permission', 'Purpose Diff']">
                <tr>
                    <x-method type="get" />
                    <x-route>/api/users</x-route>
                    <x-blank />
                    <x-td>
                        Read Users or Manage Users
                    </x-td>
                    <x-td>Shows all user data.</x-td>
                </tr>
                <tr>
                    <x-method type="get" />
                    <x-route>/api/user</x-route>
                    <x-blank />
                    <x-blank />
                    <x-td>
                        Show Current User Data
                    </x-td>
                </tr>
                <tr>
                    <x-method type="get" />
                    <x-route>/api/users/{id}</x-route>
                    <x-blank />
                    <x-td>
                        Manage Users
                    </x-td>
                    <x-blank />
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/api/users</x-route>
                    <x-json :data="['name', 'email', 'role', 'password', 'password_confirmation']" />
                    <x-td>
                        Manage Users
                    </x-td>
                    <x-td>
                        Same as <x-code>register</x-code>, but you can assign role in here.
                    </x-td>
                </tr>
                <tr>
                    <x-method type="put" />
                    <x-route>/user/profile-information</x-route>
                    <x-json :data="['name', 'email']" />
                    <x-blank />
                    <x-blank />
                </tr>
                <tr>
                    <x-method type="put" />
                    <x-route>/user/password</x-route>
                    <x-json :data="['password', 'password_confirmation']" />
                    <x-blank />
                    <x-blank />
                </tr>
                <tr>
                    <x-method type="put" />
                    <x-route>/api/users/{id}</x-route>
                    <x-json :data="['name', 'email', 'role', 'password', 'password_confirmation']" />
                    <x-td>
                        Manage Users
                    </x-td>
                    <x-td>
                        Alike to <x-code>profile-information</x-code>. But you can update your password or not and as
                        well as role and not limited to current user.
                    </x-td>
                </tr>
                <tr>
                    <x-method type="delete" />
                    <x-route>/api/users/{id}</x-route>
                    <x-blank />
                    <x-td>Manage Users</x-td>
                    <x-blank />
                </tr>
                <tr>
                    <x-method type="delete" />
                    <x-route>/api/user</x-route>
                    <x-blank />
                    <x-blank />
                    <x-td>
                        Alike to <x-code>/api/users/{id}</x-code>. But instead of deleting user based on given id, this
                        route will delete the current logged in user.
                    </x-td>
                </tr>
            </x-table>
        </x-card>
        <x-card title="Roles">
            <x-alert type="info">
                All of these routes below requires Sanctum Authentication & User Logged In by default.
            </x-alert>
            <x-alert type="warning">
                These routes will also needs Manage Roles permission to access.
            </x-alert>
            <x-table>
                <tr>
                    <x-method type="get" />
                    <x-route>/api/roles</x-route>
                    <x-blank />
                </tr>
                <tr>
                    <x-method type="get" />
                    <x-route>/api/roles/{id}</x-route>
                    <x-blank />
                </tr>
                <tr>
                    <x-method type="post" />
                    <x-route>/api/roles</x-route>
                    <x-json :data="['name, permissions[]']" />
                </tr>
                <tr>
                    <x-method type="put" />
                    <x-route>/api/roles/{id}</x-route>
                    <x-json :data="['name, permissions[]']" />
                </tr>
                <tr>
                    <x-method type="delete" />
                    <x-route>/api/roles/{id}</x-route>
                    <x-blank />
                </tr>
            </x-table>
        </x-card>
        <x-card title="Miscellaneous">
            <x-table :addon="['Permission', 'Purpose']">
                <tr>
                    <x-method type="get" />
                    <x-route>/api/permissions</x-route>
                    <x-blank />
                    <x-td>
                        Manage Roles
                    </x-td>
                    <x-td>Show All Permission Data</x-td>
                </tr>
            </x-table>
        </x-card>
    </main>
    <footer class="py-3 lg:py-5 px-2 bg-sky-300 text-center text-white mt-5">
        Test these routes by performing <span class="text-black">
            <x-code>php artisan test</x-code>
        </span>.
        Disable this page by changing App Environment to <span class="text-black">
            <x-code>production</x-code>
        </span>.
        <p>&copy; <span id="year"></span> AniBlog. All rights reserved.</p>
    </footer>

    @vite('resources/js/app.js')
    <script>
        const year = document.querySelector('#year');
        year.innerText = new Date().getFullYear();
    </script>
</body>

</html>
