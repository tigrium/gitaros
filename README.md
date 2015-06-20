# Gitáros mise diás honlapja

- API terv: [AnyPoint](https://anypoint.mulesoft.com/)
- Adatbázis terv: [Vertabelo](https://www.vertabelo.com)

Egységek:
- API
- Weblap kliens
- Android kliens

Feladatok felhasználói szemmel:
- Énekek és hozzá tartozó diák listázása szűréssel vagy nélküle
- Új ének és/vagy dia létrehozása
- Énekadatok, diaadatok szerkesztése, képcsere
- Kotta csatolása (legjobb lenne, ha Drive-on keresztül menne - szerver oldal is intézheti elfedve)
- Misesor elmentése, betöltése, módosítása, törlése (jogosultságkezelés)
- Misesor exportálása (web: letöltés zip-ben, android: letöltés vagy küldés emailben) + szövegesen kimásolni (ami e-mailbe mehet, vagy nyomtatható)
- Misesor mentésénél legyen hozzá dátum (lehessen statisztikát kérni, mikor volt utoljára használva - összes (nyilvános vagy saját) misesor vagy csak sajátok)
- Misesor klónozása
- Regisztrálás, belépés, jogosultságkezelés
- Segítségnyújtás új dia készítéséhez

Megvalósításhoz ötletek, elvárások:
- Egy dia több énekhez is tartozhasson
- Diák sorrendje módosítható legyen, egy dia többször is szerepelhessen
 - enekek, diak és valami összekötő tábla (enek_ref, dia_ref, nr - a soszáma a rendezéshez)
- Régi adatbázis átemelése viszonylag automatikusan

Extrák:
- dia készítés
- dátumfigyelés (figyelmeztet nagyböjtben, vagy beállítástól függően ilyenkor alapértelmezett keresésbeállítás)
