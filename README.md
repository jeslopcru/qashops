# Refactor clase Product

Se ha quitado redundancias
Puesto clausula de guarda para evitar "if else" innecesarios.
Evitamos el uso de '@' para que no se muestren los errores. Habria que lanzar excepciones en las clases en las que se apoya Product y quizas recogerlas y tratarlas, pero nunca es bueno evitar conocer posibles errores en la ejecución del código. 
Se crean funciones privadas, para dejar la función principal lo más legible posible. 
No ponemos comentarios porque con con el naming de las variables y funciones es suficientemente legible.

#Merge de dos csv

Si uno o ambos directorios proporcionados no existe, lanzaremos una excepción.
Si uno o ambos ficheros no se puede abrir, lanzaremos una excepción.
Igualmente no se añaden comentarios, porque con el naming de variables y funciones conseguimos que el código sea suficientemente entendible.

#Convert xml to csv

Muy parecido a la anterior clase. Solo esta esquematizada la función principal. 
Tanto la funcion setHEaders como fillCsv deberian ser recursivas para recorrer el objecto SimpleXMLElement y obtener todos los posibles valores tanto para las cabeceras como para los valores de los atributos. El ejemplo de xml que adjuntais es dificil copiarlo y he perdido mucho tiempo en que se pueda abrir bien. Por eso no me he metido más a fondo con la clase. De todas maneras me faltarian datos, pues este xml de ejemplo es muy simple, pero puede haber XML realmente complejos, y no se si quiere una clase capaz de aplanar cualquier XML o unos XML con estructuras conocidas y simples.