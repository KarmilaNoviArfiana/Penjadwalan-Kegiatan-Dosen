import Fastify from "fastify";

import { connectToWhatsApp } from "./whatsapp.js";

const start = async () => {
    // Running whtasapp service
    const { sendMessage } = await connectToWhatsApp();

    // setup fastify
    const fastify = Fastify({
        logger: true,
    });

    const sendWhatsappSchema = {
        type: "object",
        required: ["phone", "message"],
        properties: {
            phone: { type: "string" },
            message: { type: "string" },
        },
    };

    const schema = {
        body: sendWhatsappSchema,
    };

    fastify.get("/", function (request, reply) {
        reply.send({ hello: "world" });
    });

    fastify.post("/send", { schema }, function (request, reply) {
        console.log(request.body);
        sendMessage(request.body.phone, request.body.message);
        reply.send({ success: true });
    });

    // Run the server!
    fastify.listen({ port: 3000 }, function (err, address) {
        if (err) {
            fastify.log.error(err);
            process.exit(1);
        }
        // Server is now listening on ${address}
        console.log(`Server is now listening on ${address}`);
    });
};

start();
