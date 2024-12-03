import { WechatyBuilder } from 'wechaty'
import qrcodeTerminal from 'qrcode-terminal' // 导入 qrcode-terminal

const wechaty = WechatyBuilder.build() // 获取 Wechaty 实例
wechaty
  .on('scan', (qrcode, status) => {
      qrcodeTerminal.generate(qrcode, { small: true }) // 在终端打印二维码
      console.log(`Scan QR Code to login: ${status}`)
  })
  .on('login', async user => {
      console.log(`User ${user} logged in`)
  })
  .on('message', async message => {
      const room = message.room() // 获取消息所在的群
      if (room) {
          const topic = await room.topic() // 获取群名称
          const id = room.id
          console.log(`Message from room ${id}: ${topic}: ${message}`) // 打印群消息内容
          // todo：记录下所需要的群 id，判断对需要的消息进行处理
      } else {
          console.log(`Message: ${message}`) // 打印其他消息
      }
  })
wechaty.start()

// 文档：https://wechaty.gitbook.io/wechaty/zh/api/room
// https://wechaty.github.io/wechaty/#Room.find
