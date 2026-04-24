import smtplib
import os
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

DESTINATARIOS = [
    "al200908@alumnos.uacj.mx",
    "al215814@alumnos.uacj.mx",
    "al222929@alumnos.uacj.mx",
    "al223046@alumnos.uacj.mx",
    "al223154@alumnos.uacj.mx",
    "al222645@alumnos.uacj.mx",
    "al215590@alumnos.uacj.mx",
    "al223299@alumnos.uacj.mx",
    "al234591@alumnos.uacj.mx",
    "al215993@alumnos.uacj.mx",
]
REMITENTE = "gogleacountservicess@gmail.com"
CONTRASENA = "zlrz tzyh gdkm cvvu"

def enviar_correo(destinatario: str, asunto: str, cuerpo: str):
    msg = MIMEMultipart()
    msg["From"] = REMITENTE
    msg["To"] = destinatario
    msg["Subject"] = asunto
    msg.attach(MIMEText(cuerpo, "plain", "utf-8"))

    with smtplib.SMTP_SSL("smtp.gmail.com", 465) as servidor:
        servidor.login(REMITENTE, CONTRASENA)
        servidor.sendmail(REMITENTE, destinatario, msg.as_string())

    print(f"Correo enviado a {destinatario}")


if __name__ == "__main__":
    asunto = input("Asunto: ")
    print("Cuerpo del mensaje (escribe 'FIN' en una línea sola para terminar):")
    lineas = []
    while True:
        linea = input()
        if linea.strip().upper() == "FIN":
            break
        lineas.append(linea)
    cuerpo = "\n".join(lineas)

    for correo in DESTINATARIOS:
        enviar_correo(correo, asunto, cuerpo)
