# GetResponse
Get Response API

Example of use:
```
$gr = new \GetResponse\GetResponse('MyAPIkey','my360domain.com',null);

$contact = new \GetResponse\ValueObject\ContactByEmail('auser@emailaddress.com');

echo $contact->getName();

```

Create a Contact:
```
$gr = new \GetResponse\GetResponse('MyAPIkey','my360domain.com','iR');

$newContact = ( new \GetResponse\Contact() )->setName( 'John Smith' )->setEmail( 'john@smith.com' );

$gr->getContacts()->createByContact( $newContact );
```
