# UMJI-TAXI Back-End

The back-end is based on laravel.

## Environment

- MySQL 5.6
- php 5.6
- laravel 5.2

## Installation

安装过程纯属yy，错了别怪我
```
# Set up laravel
composer install

# MySQL
php artisan migrate

# Config
cp .env.example .env
vim .env
```

## API Goal

因为我懒得写wiki就丢在这里了,此处感谢 @jcpwfloi 大神

### /list/get/:id

对应ListController@Detail方法
```
{
  name: String,
  creator: String,
  from: String,
  to: String,
  peoples: [User],
  expectedNum: Number,
  state: Number
}
```
peoples给username

### /list/get

* Will support pagination plugin in next version

对应ListController@index方法

```
[{
    creator: String,
	from: String,
	to: String,
	currentNum: Number,
	expectedNum: Number,
	state: Number
}]
```

#### Explanations for State
> * 0 for active
> * 1 for completed

### /list/remove/:id
```
{
	status: 'success'
}
```
or if errored:

```
{
	status: 'error'
}
```
### POST /list/update/:id
* Asynchronously update the list via post params and return `status`


### PUT /list/add
* Asynchronously add an list element to the database and return `status`

### POST /user/authToken
* If the token does not exists, then create one and return `new` state.
* If the token already exists, return user data.