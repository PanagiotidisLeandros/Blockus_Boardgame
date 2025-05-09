# ADISE24_Blokus2

## Περιγραφή του project

Το project είναι η υλοποίηση του παιχνιδιού blockus με τέσσερις παίκτες. Η σειρά με την οποία παίζουν οι παίκτες είναι μπλε κίτρινος κόκκινος 
πράσινος πριν ξεκινήσει το παιχνίδι οι παίκτες θα πρέπει να συνδέσουν δίνοντας το όνομα τους και πατώντας το κουμπί "**ΕΙΣΟΔΟΣ ΣΤΟ ΠΑΙΧΝΙΔΙ**". 

Πατώντας το κουμπί "**ΔΕΙΞΕ ΠΑΙΚΤΕΣ ΠΑΙΧΝΙΔΙΟΥ**", ο καθένας θα μπορεί να δει το όνομα χρήστη (username) και το χρώμα που έχει επιλέξει κάθε παίκτης.

Πατώντας το κουμπί "**ΔΕΙΞΕ ΚΑΤΑΣΤΑΣΗ ΠΑΙΧΝΙΔΙΟΥ**" εμφανίζετε πληροφορίες σχετικά με το παιχνίδι όπως πιάνου είναι τώρα η γύρα (p_turn) ποια κομμάτια 
δεν έχει χρησιμοποίηση ο κάθε παίκτης (Pieces_Blue), όταν τελειώσει το παιχνίδι ο νικητής θα εμφανιστεί  στο (Result) και το σκορ το κάθε παίκτη 
στο (score) (τα σκορ εμφανίζονται με την ίδια σειρά με την οποία παίζουν  μπλε κίτρινος κόκκινος πράσινος ).

πατώντας το κουμπί "**ΔΕΙΞΕ ΝΟΜΙΜΕΣ ΚΙΝΗΣΕΙΣ**" εφόσον έχουν συνδεθεί όλοι οι παίκτες θα εμφανίζετε μια λίστα με όλες τις κίνησης που μπορεί να κάνει
 ο τρέχον παίκτης (p_turn απο το "**ΔΕΙΞΕ ΚΑΤΑΣΤΑΣΗ ΠΑΙΧΝΙΔΙΟΥ**") επίσης θα εμφανιστή και ένα νέο κουμπί το "**ΚΑΝΕ ΤΗΝ ΚΙΝΗΣΗ**".

Πατώντας το κουμπί "**ΚΑΝΕ ΤΗΝ ΚΙΝΗΣΗ**" αφού δοθεί μια νόμιμη κίνηση στο πλαίσιο από διπλά πχ 1 2 2 0 οπού ο πρώτος αριθμός ορίζει πιο από τα 21 
κομμάτια/πιόνι θα χρησιμοποίηση εδώ είναι το "1" μετά είναι η συντεταγμένες x y που θέλει να τοποθέτηση το κομμάτι εδώ είναι x=2 y=2 και τέλος 
πόσες φορές θέλει ο παίκτης να περιστρέψει δέξια το κομμάτι εδώ 0 άρα θα τοποθέτηση όπως είναι.

Πατώντας το κουμπί "**ΕΝΑΡΞΗ**" όλοι οι παίκτες αποσυνδέονται και το ταμπλό επαναφέρετε στην αρχική κατάσταση.

## API του project 


| **URI** | **Method** | **Action** | **Return Status** |
| --- | --- | --- | --- |
| blockus.php/board/| GET | Επιστρέφει σε json την τρέχουσα μορφή του board. | 200 (OK), 400 (Bad Request) |
| blockus.php/board/| POST | Κάνει reset το board στην αρχική κατάσταση. Επιστρέφει σε json την τρέχουσα μορφή του board.   | 200 (OK), 400 (Bad Request) |
| blockus.php/board/piece/{piece_id}/{cordinet_x} | PUT | Τοποθετη το blockus πιόνι με id = piece_id (τιμη 1 -21 για τα 21 πιονια blockus) στην θεση με συντεταγμενη x ισο με cordinet_x (x,y) και y = cordinet_y με δεξιες περιστροφες = rotation. Ορίσματα: cordinet_y, rotation. Θα επιστρέψει την νέα κατάσταση των κελιών όλου του πινακα. | 200 (OK), 400 (Bad Request) |
| blockus.php/players/{p}| GET | Επιστρέφει σε json τo όνομα και το χρώμα του παίκτη p=B , R , Y ή G   | 200 (OK), 400 (Bad Request) |
| blockus.php/players/{p} | PUT | Ορίζει το όνομα του παίκτη p σε name. Ορίσματα: name. | 200 (OK), 400 (Bad Request) |
| blockus.php/players/ | GET | Επιστρέφει σε json τα ονόματα και χράματα όλων των παικτών. | 200 (OK), 400 (Bad Request) |
| blockus.php/status/  | GET |  Επιστρέφει σε json το status του παιχνιδιού. | 200 (OK), 400 (Bad Request) |

<!-- ## Links -->

<!-- https://drive.google.com/drive/u/0/folders/1AAQ-vGNLVEusxUZmTIbUXNXMpEbqnLt1?q=sharedwith:public%20parent:1AAQ-vGNLVEusxUZmTIbUXNXMpEbqnLt1 -->

<!-- https://users.iee.ihu.gr/~iee2020246/ADISE24_Blokus2/www/ (inactive) -->

