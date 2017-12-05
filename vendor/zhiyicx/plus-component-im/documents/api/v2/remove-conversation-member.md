# 剔除对话中的成员

##接口地址
`/api/v2/im/conversations/members/{cid}/{uid}`

##请求方法
`DELETE `

##特别说明:
 - 地址中的cid为对话id,如果该对话id不存在会返回错误,uid为需要移除的成员uid;
 - 本接口如果对话中没有该成员也会返回成功,但只要是合法(符合逻辑)操作,不会出现该情况

## 返回体

```
Status 204 ON Content
```

## 返回字段
| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| cid  | int      | yes      | 当前操作的聊天对话ID |
|uid	|int		|yes	 |当前剔除的成员uid|
