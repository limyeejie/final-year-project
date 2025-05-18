import sys
import numpy as np
import pandas as pd
from statsmodels.tsa.arima.model import ARIMA
import json

# Retrieve the data from PHP via command-line arguments 
data = sys.argv[1]
data = json.loads(data)

# Extract volunteer trends from the input data 
months = list(range(1, len(data) + 1))
volunteers = [entry['new_volunteers'] for entry in data]

# Convert the data into a pandas Series for ARIMA processing
volunteers_series = pd.Series(volunteers, index=pd.date_range(start='2021-01', periods=len(volunteers), freq='M'))

# Fit an ARIMA model
model = ARIMA(volunteers_series, order=(5,1,0))  
model_fit = model.fit()

# Predict the next month's volunteer count
forecast = model_fit.forecast(steps=1)
predicted_volunteers = int(forecast[0]) 

# Round the prediction and ensure it's at least 1
predicted_volunteers = max(1, round(predicted_volunteers))

# Output the result in JSON format
print(json.dumps({"predicted_volunteers": predicted_volunteers}))
