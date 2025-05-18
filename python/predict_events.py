import sys
import pandas as pd
import mysql.connector
from sklearn.neighbors import KNeighborsClassifier
import json

# Database connection
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="eventmanagementsystem"
)

# Fetch participants data with event categories
query = """
SELECT 
    p.userId,
    COUNT(p.eventId) AS total_events_participated,
    e.event_category
FROM 
    participants p
JOIN 
    events e ON p.eventId = e.id
GROUP BY 
    p.userId, e.event_category
"""

data = pd.read_sql(query, db)

# Close database connection
db.close()

# Check if data exists
if data.empty:
    print(json.dumps({"error": "No data available for prediction"}))
    sys.exit()

# Map event categories to numerical labels
category_mapping = {category: idx for idx, category in enumerate(data['event_category'].unique())}
data['event_category_label'] = data['event_category'].map(category_mapping)

# Prepare data for KNN
X = data[['total_events_participated']]  
y = data['event_category_label']        

# Train KNN model
knn = KNeighborsClassifier(n_neighbors=3)
knn.fit(X, y)

# Predict for a new user
new_user = [[5]]  
predicted_category_label = knn.predict(new_user)

# Map the predicted label back to the event category
predicted_category = list(category_mapping.keys())[list(category_mapping.values()).index(predicted_category_label[0])]

# Output the predicted category in JSON format
print(json.dumps({"predicted_event_category": predicted_category}))


