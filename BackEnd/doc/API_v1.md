# Carpool-back-end API v1.0

## Success and Error

The first line of return is always 'version'. Then, the second line clear indicates whether the request is dealed correctly.

For error,

| value | meaning |
| ----- | ------- |
| 0     | Success |
| 1     | Error   |


## GET /list/get

### params

No param.

### return

```
{
   'version": '1.0',
    'error': 0,
    'msg': [
        {
            'act_id': 2
            'creator': 'Dustism',
            'name': 'Nvzhuang',
            'people': ['JasonQSY', 'Songqun', 'Luke'],
            'from': 'D20',
            'to': 'D21',
            'expectedNum': 3,
            'state': 0
        },
        {
            ....
        }
    ]
}
```

## GET /list/get/:id

### params

No param.

### return

```
{
    'version": '1.0',
    'error': 0,
    'msg': {
        'act_id': 2
        'creator': 'Dustism',
        'name': 'Nvzhuang',
        'people': ['JasonQSY', 'Songqun', 'Luke'],
        'from': 'D20',
        'to': 'D21',
        'expectedNum': 3,
        'state': 0
    },
}
```

## GET /user/login

### params

HTTP 302 redirect by Weixingate API. No param is necessary. However, it should be called in wechat embedded browser.

### return

```
{
   'version': '1.0',
   'error': 0,
   'msg': ...
}
```

## POST /list/add

It is unnecessary to provide creator since the creator would be that who has logged in.

### params
| param | value | comment |
| ----- | ----- | ------- |
| name  | 'Nvzhuang' | The title of a deal |
| from  | 'D20' |  |
| to | 'D21' | |
| expectedNum | 3 | excluding the creator |


### return

```
   'version': '1.0',
   'error': 0,
   'msg': ...
```

## POST /list/update

### params

The offerd data will be changed in the database.

| param | value | comment |
| ----- | ----- | ------- |
| name  | 'Nvzhuang' | The title of a deal |
| from  | 'D20' |  |
| to | 'D21' | |
| expectedNum | 3 | excluding the creator |
| state | 0 or 1 | 0 means active, 1 means completed |

### return

```
   'version': '1.0',
   'error': 0,
   'msg': ...
```


## GET /list/drop

### params 

The only param is the act_id.

### return

```
   'version': '1.0',
   'error': 0,
   'msg': ...
```


## GET /user/profile/:id

Get the profile of somebody.

### params

No param

### return

```
   'version': '1.0',
   'error': 0,
   'msg': {
        'name': 'dustism',
        'email': 'nvzhuang@sjtu.edu.cn'
   }
```

## POST /user/profile

Update the profile

### params

| param | value | comment |
| ----- | ----  | --------|
| username | 'nvzhuang' | |


### return

```
   'version': '1.0',
   'error': 0,
   'msg': ...
```
