
# Custom Vendors for WooCommerce - Plugin WordPress

Custom Vendors for WooCommerce es un sistema de gestión de agentes de ventas que permite al cliente final (Customer) indicar en el checkout de Woocommerce, el agente que lo atendió o lo asesoró en la venta. Facilita el seguimiento del número de ventas por agente o vendedor.



## ¿Qué exactamente hace este plugin?

- Crea un CPT Llamado "Vendedores" en tu instalación de WordPress, desde el cuál podrás añadir los vendedores de tu tienda o negocio.
- El CPT contiene los campos Nombre del vendedor, Código del vendedor, Ubicación o dirección.
- Permite al Customer o cliente final, seleccionar por si mismo, el vendedor que lo asesoró o atendió desde una lista desplegable en el Checkout de Woocommerce.
- Permite al administrador de la tienda, hacer un seguimiento del número de ventas de cada vendedor.
- Genera un shortcode que muestra una tabla con la lista de los vendeores y el número de ventas que llevan hasta el momento. El shortcode funciona en cualquier página del sitio web.

## Uso de metadatos o metakey para mostrar información

- Con el metakey **_vendor_code** puedes mostrar el código del vendedor
- Con el metakey **_cv_sales_count** puedes mostrar el número de ventas que lleva el vendedor.
- Con la metakey **_vendor_address** puedes mostrar la ubicación o dirección del vendedor.
- Para mostrar una lista de vendedores con sus respectivas ventas debes usar el Shortcode **[list_vendors_sales]**
- El nombre del Vendedor no se almacena como una meta key específica porque es el título del post. Para obtener el nombre del vendedor, puedes usar el título del post de tipo 'vendedor'. Ejemplo: Asociando el ID del vendedor, puedes obtener el nombre utilizando la función **$vendor_title = get_the_title($vendor_id);**.

## Tips y Recomendaciones

Con la ayuda de constructores visuales como elementor, bricks, etc. Puedes crear template de single post y loop item para personalizar la visualización y mostrar un listing de los vendedores en tu web. Se adjunta imagen en el apartado de capturas de pantalla sobre como se vería un listing de CPT vendedores creado con elementor pro y su Loop Grid widget.


### Otros requisitos del sistema

- El plugin se integra con Woocommerce, por lo que es requisito tenerlo instalado.
- Versión mínima de Wordpres testeada: 6.5.2
- PHP Version:7.4 o mayor


## Screenshots

### Captura de pantalla - CPT Vendedores creado en WordPress
[![CPT-en-Word-Press.png](https://i.postimg.cc/CxZrC2Jk/CPT-en-Word-Press.png)](https://postimg.cc/G4CjdqWm)

### Captura de pantalla - Campos del CPT Vendedores
[![Campos-del-CPT-Vendedores.png](https://i.postimg.cc/52Z7smkG/Campos-del-CPT-Vendedores.png)](https://postimg.cc/75nn6S4M)

### Captura de pantalla - Se agrega en el Checkout el apartado "Seleccione un Vendedor"
[![imagen-Checkout-El-customer-selecciona-el-vendedor-por-el-mismo.png](https://i.postimg.cc/xdMshbwd/imagen-Checkout-El-customer-selecciona-el-vendedor-por-el-mismo.png)](https://postimg.cc/676LGT5s)

### Captura de pantalla - Tabla creada por el Shortcode del plugin [list_vendors_sales]
[![Tabla-creada-con-el-Shortcode-del-plugin.png](https://i.postimg.cc/ncSwDxKn/Tabla-creada-con-el-Shortcode-del-plugin.png)](https://postimg.cc/PPZMgBF7)

### Captura de pantalla - Creación del campo Vendedor en el pedido de Woocommerce para identificar el vendedor
[![Creaci-n-del-campo-en-el-pedido-para-seguimiento.png](https://i.postimg.cc/wMtXS1Mv/Creaci-n-del-campo-en-el-pedido-para-seguimiento.png)](https://postimg.cc/8fSfvPhQ)

## Authors

- [@ferchounite](https://github.com/Ferchounite/)


## FAQ

#### ¿El plugin es personalizable, es decir, se puede cambiar el nombre del CPT?

De momento puedes personalizarlo via código, puedes cambiar el nombre del CPT, etiquetas, agregar o modificar campos personalizados, etc.

#### ¿El plugins es gratuito?

Si es open source, puede ser modificado y sus límites están establecidos en la licencia GPL 3.

## Support

Para soporte, envía un email a fj.ardila09@gmail.com o ingrese a [fernandoardila.dev](https://fernandoardila.dev)


## License

[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)

