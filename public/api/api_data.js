define({ "api": [
  {
    "type": "post",
    "url": "/address/create",
    "title": "创建收货地址",
    "name": "AddressCreate",
    "group": "Address",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "phone_num",
            "description": "<p>手机号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "province",
            "description": "<p>省</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "city",
            "description": "<p>市</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "area",
            "description": "<p>区</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "address",
            "description": "<p>详细地址 不能为空</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "default_flag",
            "description": "<p>是否默认 Y 默认 N 不默认</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Address/AddressController.php",
    "groupTitle": "Address",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/address/create"
      }
    ]
  },
  {
    "type": "get",
    "url": "/address/delete",
    "title": "删除地址",
    "name": "AddressDelete_____",
    "group": "Address",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "address_id",
            "description": "<p>地址id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Address/AddressController.php",
    "groupTitle": "Address",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/address/delete"
      }
    ]
  },
  {
    "type": "post",
    "url": "/address/edit",
    "title": "编辑收货地址",
    "name": "AddressEdit",
    "group": "Address",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "address_id",
            "description": "<p>地址ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "phone_num",
            "description": "<p>手机号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "province",
            "description": "<p>省</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "city",
            "description": "<p>市</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "area",
            "description": "<p>区</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "address",
            "description": "<p>详细地址 不能为空</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "default_flag",
            "description": "<p>是否默认 Y 默认 N 不默认</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"response\": {\n          \"data\": [],\n          \"status\": 1,\n          \"msg\": \"更新成功\"\n     }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Address/AddressController.php",
    "groupTitle": "Address",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/address/edit"
      }
    ]
  },
  {
    "type": "get",
    "url": "/address/get",
    "title": "获取地址详情",
    "name": "AddressGet_______",
    "group": "Address",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "address_id",
            "description": "<p>地址id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Address/AddressController.php",
    "groupTitle": "Address",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/address/get"
      }
    ]
  },
  {
    "type": "get",
    "url": "/address/list",
    "title": "获取地址列表",
    "name": "AddressList_______",
    "group": "Address",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Address/AddressController.php",
    "groupTitle": "Address",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/address/list"
      }
    ]
  },
  {
    "type": "get",
    "url": "/client",
    "title": "用户详情",
    "name": "____",
    "group": "Client",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Client/ClientController.php",
    "groupTitle": "Client",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/client"
      }
    ]
  },
  {
    "type": "get",
    "url": "/client/flow_list",
    "title": "资金变更流水",
    "name": "______",
    "group": "Client",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>提现类型 1 增加冻结金额 2 可提现金额减少 3 减少冻结金额 4 可提现金额增加</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>返回条数</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Client/ClientController.php",
    "groupTitle": "Client",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/client/flow_list"
      }
    ]
  },
  {
    "type": "get",
    "url": "/client/check",
    "title": "是否绑定机器人",
    "name": "_______",
    "group": "Client",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Client/ClientController.php",
    "groupTitle": "Client",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/client/check"
      }
    ]
  },
  {
    "type": "get",
    "url": "/good",
    "title": "获取商品详情",
    "name": "______",
    "group": "Good",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "good_id",
            "description": "<p>商品ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Goods/GoodController.php",
    "groupTitle": "Good",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/good"
      }
    ]
  },
  {
    "type": "post",
    "url": "/order/cancel",
    "title": "取消订单",
    "name": "OrderCancel",
    "group": "Order",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_id",
            "description": "<p>订单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Order/OrderController.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/order/cancel"
      }
    ]
  },
  {
    "type": "post",
    "url": "/order/confirm",
    "title": "确认收货",
    "name": "OrderConfirm",
    "group": "Order",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_id",
            "description": "<p>订单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Order/OrderController.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/order/confirm"
      }
    ]
  },
  {
    "type": "post",
    "url": "/order/create",
    "title": "商品下单支付",
    "name": "OrderCreate",
    "group": "Order",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "address_id",
            "description": "<p>地址ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "open_invoice_flag",
            "description": "<p>是否开具发票 Y 开 N否</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "good_id",
            "description": "<p>商品id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "color",
            "description": "<p>商品颜色</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "combo",
            "description": "<p>套餐选择</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "quantity",
            "description": "<p>商品数量</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "buyer_msg",
            "description": "<p>买家留言</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "invoice_type",
            "description": "<p>发票类型 0-个人，1-公司 open_invoice_flag为Y时必填</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "detail",
            "description": "<p>发票明细（选填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>收票人邮箱（选填）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "phone_num",
            "description": "<p>收票人电话（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "title",
            "description": "<p>发票抬头 open_invoice_flag为Y时必填</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "tax_code",
            "description": "<p>发票税号 invoice_type为1时必填</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "parent_id",
            "description": "<p>推广人</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Order/OrderController.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/order/create"
      }
    ]
  },
  {
    "type": "get",
    "url": "/order/get",
    "title": "获取订单详情",
    "name": "OrderDetail",
    "group": "Order",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_id",
            "description": "<p>订单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Order/OrderController.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/order/get"
      }
    ]
  },
  {
    "type": "get",
    "url": "/order/list",
    "title": "根据订单状态获取订单列表",
    "name": "OrderList",
    "group": "Order",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_status",
            "description": "<p>0-未支付，1-已支付，2-待发货，3-已发货，4-已完成，5-异常，6-申请退货，7-确认退货，8-已退货 9-已取消 -1 全部</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>每页显示条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_id",
            "description": "<p>订单ID</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_number",
            "description": "<p>商品订单</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_type",
            "description": "<p>0-预付款，1-货到付款</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_status",
            "description": "<p>ORDER_STATUS：0-已下单，1-已支付，2-待发货，3-已发货，4-已完成，5-异常，6-申请退货，7-确认退货，8-已退货 9-已取消</p>"
          },
          {
            "group": "Success 200",
            "type": "datetime",
            "optional": false,
            "field": "order_date",
            "description": "<p>下单时间</p>"
          },
          {
            "group": "Success 200",
            "type": "datetime",
            "optional": false,
            "field": "pay_date",
            "description": "<p>支付时间</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "contract_id",
            "description": "<p>收货地址ID</p>"
          },
          {
            "group": "Success 200",
            "type": "datetime",
            "optional": false,
            "field": "completion_date",
            "description": "<p>订单完成时间</p>"
          },
          {
            "group": "Success 200",
            "type": "datetime",
            "optional": false,
            "field": "return_date",
            "description": "<p>退货时间</p>"
          },
          {
            "group": "Success 200",
            "type": "datetime",
            "optional": false,
            "field": "request_close_date",
            "description": "<p>订单关闭日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "open_invoice_flag",
            "description": "<p>是否开发票</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "good_id",
            "description": "<p>商品id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "good_name",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "color",
            "description": "<p>商品颜色</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "combo",
            "description": "<p>套餐</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "total_price",
            "description": "<p>商品总金额</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "unit_price",
            "description": "<p>单价</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "quantity",
            "description": "<p>数量</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "robot_id",
            "description": "<p>关联机器人ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Order/OrderController.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/order/list"
      }
    ]
  },
  {
    "type": "post",
    "url": "/pay/withdraw_list",
    "title": "提现记录",
    "name": "PayWithdraw",
    "group": "Pay",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1 获取个人的  2 获取所有的</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>0 提现失败 1 提现成功 2 提现中</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Pay/PayController.php",
    "groupTitle": "Pay",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/pay/withdraw_list"
      }
    ]
  },
  {
    "type": "post",
    "url": "/pay/withdraw",
    "title": "提现",
    "name": "withdraw",
    "group": "Pay",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "amount",
            "description": "<p>提现金额</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>返回的数据结构体</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 执行成功 0 为执行失败</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>执行信息提示</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Api/Controllers/Pay/PayController.php",
    "groupTitle": "Pay",
    "sampleRequest": [
      {
        "url": "https://wxapp.honeybot.cn/api/pay/withdraw"
      }
    ]
  }
] });
