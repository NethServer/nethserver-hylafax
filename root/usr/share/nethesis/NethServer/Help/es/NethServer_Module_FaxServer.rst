===============
Servidor de fax
===============

El servidor de fax le permite enviar y recibir faxes a través de un módem conectado directamente a un puerto del servidor (COM o USB) o a través de un módem IAX virtual.

El módem debe ser compatible con el envío y la recepción de faxes de preferencia en la clase 1 o 1.0 (las clases 2, 2.0 y 2.1 también son compatibles).

General
=======

Código del país
     El prefijo internacional que se antepone a su número de fax. 
Prefijo
    Código de área.
Número de fax
    El número de fax del remitente.
Remitente (TSI)
    La ETI se imprimirá en el encabezado del fax del destinatario, por lo general en la fila superior. Es posible introducir el número de fax o el nombre de una longitud total de hasta 20 caracteres (se recomienda el nombre de la empresa). Sólo se permiten caracteres alfanuméricos.


Módem
=====

Módem
    El puerto físico (COM o USB) a la que el módem está conectado o un módem fax virtual

    * Estándar de dispositivo: le permite seleccionar el dispositivo de una lista de los puertos comunes
    * Dispositivo personalizado: permite especificar un dispositivo personalizado para ser utilizado como un módem fax. * Debe ser el nombre de un dispositivo en el sistema. *
Modo
    Especifica el modo de funcionamiento del dispositivo seleccionado. Los modos disponibles son:

    * Enviar y recibir: el módem se utiliza para enviar y recibir faxes
    * Recibir sólo : el módem sólo se utilizará para la recepción de faxes
    * Envair sólo : el módem sólo se utilizará para el envío de faxes
Prefijo PBX
    Si el módem de fax está conectado a una centralita , puede que tenga que introducir un código de acceso para "obtener una línea externa."
    Si el módem está conectado directamente a una línea o el PBX requiere ningún código, deje el campo vacío .
    Si estás detrás de un PBX , introduzca el prefijo que se debe marcar .

Esperar tono de marcación
    Algunos módems no son capaces de reconocer el tono de marcación (sobre todo si está conectado a un PBX ) y no marcar el número señalando la ausencia de tono (de error " No hay tono de marcado" ).

    Para configurar el módem para que ignore la ausencia de la línea y marque inmediatamente el número seleccionar Desactivado. La configuración recomendada es "Habilitado", es posible que desee desactivar *Espere el tono de marcación* sólo en caso de problemas.


Notificaciones por correo electrónico
=====================================

Formato de los faxes recibidos
    De forma predeterminada , el servidor de fax envía los faxes recibidos como mensajes de correo electrónico con un archivo adjunto. Especifique la dirección de correo electrónico donde se entregarán los faxes, y uno o más formatos del archivo adjunto. Para no recibir el fax como archivo adjunto, pero sólo una notificación de recepción, anule la selección de todos los formatos.

Faxes recibidos hacia adelante

    * Grupo "faxmaster"
        Por defecto, los faxes recibidos se envían a *faxmaster*: si  un usuario necesita para recibir los faxes entrantes , debe añadirse a esta grupo.
    * Correo electrónico externo
        Introduzca una dirección de correo electrónico externa en caso de que desee enviar los faxes recibidos a una dirección de correo electrónico no en este servidor

Formato de faxes enviados
    Si lo solicita el cliente, el servidor envía una notificación por correo electrónico con un archivo adjunto. Seleccione el formato en el que prefiere recibir el fax. Anule la selección de todas las opciones si no desea recibir el fax adjunto.
    

Añadir notificación de entrega
    Si se selecciona, se agrega un informe de notificación de entrega en el correo electrónico enviado por fax.



Funciones adicionales
=====================

Ver los faxes enviados por el cliente
    Los clientes de fax también le permiten ver todos los faxes entrantes. Si, por razones de confidencialidad, se desea filtrar los faxes recibidos, desactive esta opción.

Imprimir automáticamente los faxes recibidos
    Imprimir automáticamente todos los faxes recibidos en una impresora compatible con PCL5 configurada en el servidor. La impresora debe estar seleccionado con la caída adecuada en el menú desplegable.

SambaFax
    Al eleccionar esta opción, el servidor de fax puede poner a disposición de la red de área local de una impresora virtual llamada "sambafax" que tiene que configurar el cliente, seleccionando el controlador Apple LaserWriter 16/600 PS. Los documentos impresos en la impresora de red sambafax deben contener la frase exacta "Número de fax:" seguido del número de fax del destinatario. 

Enviar informe diario
    Enviar un informe diario al administrador
