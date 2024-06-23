import makeWASocket, {
    DisconnectReason,
    useMultiFileAuthState,
} from "@whiskeysockets/baileys";

const { state, saveCreds } = await useMultiFileAuthState("auth_info_baileys");

async function connectToWhatsApp() {
    const sock = makeWASocket.makeWASocket({
        auth: state,
        // can provide additional config here
        printQRInTerminal: true,
    });
    sock.ev.on("connection.update", (update) => {
        const { connection, lastDisconnect } = update;
        if (connection === "close") {
            const shouldReconnect =
                lastDisconnect.error?.output?.statusCode !==
                DisconnectReason.loggedOut;
            console.log(
                "connection closed due to ",
                lastDisconnect.error,
                ", reconnecting ",
                shouldReconnect
            );
            // reconnect if not logged out
            if (shouldReconnect) {
                connectToWhatsApp();
            }
        } else if (connection === "open") {
            console.log("opened connection");
        }
    });
    sock.ev.on("messages.upsert", async (m) => {
        console.log(JSON.stringify(m, undefined, 2));

        console.log("replying to", m.messages[0].key.remoteJid);
    });
    sock.ev.on("creds.update", saveCreds);

    const sendMessage = async (phone, message) => {
        
        let res = await sock.sendMessage(phone + "@s.whatsapp.net", { text: message });
        console.log("res", res)
    };

    return { sock, sendMessage };
}

export { connectToWhatsApp };
// // run in main file
// connectToWhatsApp();
